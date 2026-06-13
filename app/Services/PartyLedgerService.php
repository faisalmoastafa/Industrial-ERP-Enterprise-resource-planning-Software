<?php

namespace App\Services;

use App\Models\PartyPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\Purchase\Entities\Purchase;
use Modules\Sale\Entities\Sale;

class PartyLedgerService
{
    public function __construct(private PartyPaymentSchemaService $schema)
    {
        $this->schema->ensure();
    }

    public function balances(?string $partyType = null, ?string $name = null): array
    {
        $rows = collect();

        if (!$partyType || $partyType === 'customer') {
            $rows = $rows->merge($this->customerBalances($name));
        }

        if (!$partyType || $partyType === 'supplier') {
            $rows = $rows->merge($this->supplierBalances($name));
        }

        return [
            'receivable' => $rows->where('balance', '>', 0)->values(),
            'payable' => $rows->where('balance', '<', 0)->values(),
            'net_receivable' => $rows->where('balance', '>', 0)->sum('balance'),
            'net_payable' => abs($rows->where('balance', '<', 0)->sum('balance')),
        ];
    }

    public function monthlyChart(): array
    {
        $months = collect();
        $start_date = Carbon::now()->subMonths(11)->startOfMonth();

        foreach (range(-11, 0) as $i) {
            $key = Carbon::now()->addMonths($i)->format('m-Y');
            $months->put($key, ['receivable' => 0, 'payable' => 0]);
        }

        // --- 1. Compute Historical Baseline (Everything before the 12-month window) ---
        $runningReceivable = 0;
        $runningPayable = 0;

        // Historical Customers
        $historicalCustomers = Customer::where('created_at', '<', $start_date)->get();
        foreach ($historicalCustomers as $c) {
            $amt = (float) $c->opening_balance;
            if ($amt > 0) $runningReceivable += $amt;
            else $runningPayable += abs($amt);
        }

        // Historical Suppliers
        $historicalSuppliers = Supplier::where('created_at', '<', $start_date)->get();
        foreach ($historicalSuppliers as $s) {
            $amt = (float) $s->opening_balance;
            if ($amt > 0) $runningPayable += $amt;
            else $runningReceivable += abs($amt);
        }

        // Historical Sales & Purchases
        $runningReceivable += ((float) Sale::where('date', '<', $start_date->toDateString())->sum('due_amount')) / 100;
        $runningPayable += ((float) Purchase::where('date', '<', $start_date->toDateString())->sum('due_amount')) / 100;

        // Historical Party Payments
        PartyPayment::where('date', '<', $start_date->toDateString())->get()->each(function($row) use (&$runningReceivable, &$runningPayable) {
            $amount = (float) $row->amount;
            if ($row->party_type === 'customer') {
                if ($row->payment_type === 'pay_later') $runningReceivable += $amount;
                else $runningPayable += $amount;
            } else {
                if ($row->payment_type === 'pay_later') $runningPayable += $amount;
                else $runningReceivable += $amount;
            }
        });

        // --- 2. Load the 12-month window activity ---
        $monthlyActivity = collect();
        foreach ($months->keys() as $key) {
            $monthlyActivity->put($key, ['receivable' => 0, 'payable' => 0]);
        }

        // Window Customers
        Customer::where('created_at', '>=', $start_date)->get()->each(function($c) use ($monthlyActivity) {
            $month = $c->created_at->format('m-Y');
            if ($monthlyActivity->has($month)) {
                $data = $monthlyActivity->get($month);
                $amt = (float) $c->opening_balance;
                if ($amt > 0) $data['receivable'] += $amt;
                else $data['payable'] += abs($amt);
                $monthlyActivity->put($month, $data);
            }
        });

        // Window Suppliers
        Supplier::where('created_at', '>=', $start_date)->get()->each(function($s) use ($monthlyActivity) {
            $month = $s->created_at->format('m-Y');
            if ($monthlyActivity->has($month)) {
                $data = $monthlyActivity->get($month);
                $amt = (float) $s->opening_balance;
                if ($amt > 0) $data['payable'] += $amt;
                else $data['receivable'] += abs($amt);
                $monthlyActivity->put($month, $data);
            }
        });

        // Window Sales
        Sale::where('date', '>=', $start_date->toDateString())
            ->select([DB::raw("strftime('%m-%Y', date) as month"), DB::raw("SUM(due_amount) as sum_amount")])
            ->groupBy('month')->get()->each(function($row) use ($monthlyActivity) {
                if ($monthlyActivity->has($row->month)) {
                    $data = $monthlyActivity->get($row->month);
                    $data['receivable'] += ((float) $row->sum_amount) / 100;
                    $monthlyActivity->put($row->month, $data);
                }
            });

        // Window Purchases
        Purchase::where('date', '>=', $start_date->toDateString())
            ->select([DB::raw("strftime('%m-%Y', date) as month"), DB::raw("SUM(due_amount) as sum_amount")])
            ->groupBy('month')->get()->each(function($row) use ($monthlyActivity) {
                if ($monthlyActivity->has($row->month)) {
                    $data = $monthlyActivity->get($row->month);
                    $data['payable'] += ((float) $row->sum_amount) / 100;
                    $monthlyActivity->put($row->month, $data);
                }
            });

        // Window Party Payments
        PartyPayment::query()
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                'party_type',
                'payment_type',
                DB::raw('SUM(amount) as sum_amount'),
            ])
            ->where('date', '>=', $start_date->toDateString())
            ->groupBy('month', 'party_type', 'payment_type')
            ->get()
            ->each(function ($row) use ($monthlyActivity) {
                if ($monthlyActivity->has($row->month)) {
                    $amount = ((float) $row->sum_amount) / 100;
                    $data = $monthlyActivity->get($row->month);

                    if ($row->party_type === 'customer') {
                        if ($row->payment_type === 'pay_later') $data['receivable'] += $amount;
                        else $data['payable'] += $amount;
                    } else {
                        if ($row->payment_type === 'pay_later') $data['payable'] += $amount;
                        else $data['receivable'] += $amount;
                    }
                    $monthlyActivity->put($row->month, $data);
                }
            });

        // --- 3. Construct Cumulative Chart ---
        foreach ($months->keys() as $monthKey) {
            $activity = $monthlyActivity->get($monthKey);
            $runningReceivable += $activity['receivable'];
            $runningPayable += $activity['payable'];
            
            $months->put($monthKey, [
                'receivable' => $runningReceivable,
                'payable' => $runningPayable
            ]);
        }

        return [
            'months' => $months->keys()->values()->all(),
            'receivable' => $months->pluck('receivable')->map(fn ($value) => round($value, 2))->values()->all(),
            'payable' => $months->pluck('payable')->map(fn ($value) => round($value, 2))->values()->all(),
        ];
    }

    public function conversionCost(?string $startDate = null, ?string $endDate = null): float
    {
        if (!class_exists(\Modules\Manufacturing\Entities\ProductionBatchExpense::class)) {
            return 0;
        }

        $query = \Modules\Manufacturing\Entities\ProductionBatchExpense::query();

        if (!Schema::hasTable('production_batch_expenses')) {
            return 0;
        }

        if ($startDate && $endDate && Schema::hasColumn('production_batch_expenses', 'date')) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return ((float) $query->sum('amount')) / 100;
    }

    private function customerBalances(?string $name)
    {
        // FIX 5: Use SQL aggregation instead of loading all payments per party
        // to avoid N+1 queries and PHP memory exhaustion with many customers.
        $invoiceDues = Sale::selectRaw('customer_id, SUM(due_amount) as total_due')
            ->groupBy('customer_id')
            ->pluck('total_due', 'customer_id');

        $paymentSums = PartyPayment::where('party_type', 'customer')
            ->selectRaw("party_id,
                SUM(CASE WHEN payment_type = 'pay_later' THEN amount ELSE -amount END) as net_amount")
            ->groupBy('party_id')
            ->pluck('net_amount', 'party_id');

        return Customer::query()
            ->when($name, fn ($query) => $query->where('customer_name', 'like', '%' . $name . '%'))
            ->get()
            ->map(function ($customer) use ($invoiceDues, $paymentSums) {
                $invoiceDue     = ((float) ($invoiceDues[$customer->id] ?? 0)) / 100;
                $paymentBalance = (float) ($paymentSums[$customer->id] ?? 0) / 100;

                return [
                    'party_type' => 'Customer',
                    'name'       => $customer->customer_name,
                    'phone'      => $customer->customer_phone,
                    'balance'    => $customer->opening_balance + $invoiceDue + $paymentBalance,
                ];
            });
    }

    private function supplierBalances(?string $name)
    {
        // FIX 5: Use SQL aggregation instead of loading all payments per party
        $invoiceDues = Purchase::selectRaw('supplier_id, SUM(due_amount) as total_due')
            ->groupBy('supplier_id')
            ->pluck('total_due', 'supplier_id');

        $paymentSums = PartyPayment::where('party_type', 'supplier')
            ->selectRaw("party_id,
                SUM(CASE WHEN payment_type = 'pay_later' THEN -amount ELSE amount END) as net_amount")
            ->groupBy('party_id')
            ->pluck('net_amount', 'party_id');

        return Supplier::query()
            ->when($name, fn ($query) => $query->where('supplier_name', 'like', '%' . $name . '%'))
            ->get()
            ->map(function ($supplier) use ($invoiceDues, $paymentSums) {
                $invoiceDue     = ((float) ($invoiceDues[$supplier->id] ?? 0)) / 100;
                $paymentBalance = (float) ($paymentSums[$supplier->id] ?? 0) / 100;

                return [
                    'party_type' => 'Supplier',
                    'name'       => $supplier->supplier_name,
                    'phone'      => $supplier->supplier_phone,
                    'balance'    => -$supplier->opening_balance - $invoiceDue + $paymentBalance,
                ];
            });
    }

    private function applyInvoiceMonthly($months, $query, string $column, string $type): void
    {
        $query->where('date', '>=', Carbon::today()->subYear()->toDateString())
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM($column) as amount"),
            ])
            ->groupBy('month')
            ->get()
            ->each(function ($row) use ($months, $type) {
                if (!$months->has($row->month)) {
                    return;
                }

                $data = $months->get($row->month);
                $data[$type] += ((float) $row->amount) / 100;
                $months->put($row->month, $data);
            });
    }
}
