<?php

namespace App\Http\Controllers;

use App\Services\FinancialMetricsService;
use App\Services\PartyLedgerService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Expense\Entities\Expense;
use Modules\HRM\Entities\Payroll;
use Modules\Income\Entities\Income;
use Modules\Product\Entities\Product;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class HomeController extends Controller
{

    public function index(FinancialMetricsService $financialMetrics, PartyLedgerService $partyLedger) {
        $metrics = $financialMetrics->summary();
        $partyBalances = $partyLedger->balances();
        $profitBars = $this->dashboardProfitBars();
        $low_stock_products = Product::whereColumn('product_quantity', '<=', 'product_stock_alert')->count();

        return view('home', [
            'revenue'          => $metrics['net_sales_amount'],
            'sales_total'      => $metrics['sales_amount'],
            'purchases_total'  => $metrics['purchases_amount'],
            'sale_returns'     => $metrics['sale_returns_amount'],
            'purchase_returns' => $metrics['purchase_returns_amount'],
            'expenses_total'   => $metrics['expenses_amount'],
            'low_stock_products' => $low_stock_products,
            'completed_sales' => $metrics['total_sales'],
            'completed_purchases' => $metrics['total_purchases'],
            'profit'           => $metrics['net_result_amount'],
            'net_payable'      => $partyBalances['net_payable'],
            'net_receivable'   => $partyBalances['net_receivable'],
            'conversion_cost'  => $partyLedger->conversionCost(),
            'profitBars'        => $profitBars
        ]);
    }

    private function dashboardProfitBars(): array {
        $weekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY)->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();
        $dateExpression = "strftime('%Y-%m-%d', date)";

        $salesByDay = Sale::where('status', 'Completed')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->select([
                DB::raw("$dateExpression as day"),
                DB::raw('SUM(total_amount) as amount'),
            ])
            ->groupBy(DB::raw($dateExpression))
            ->pluck('amount', 'day');

        $purchasesByDay = Purchase::where('status', 'Completed')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->select([
                DB::raw("$dateExpression as day"),
                DB::raw('SUM(total_amount) as amount'),
            ])
            ->groupBy(DB::raw($dateExpression))
            ->pluck('amount', 'day');

        $expensesByDay = Expense::whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->select([
                DB::raw("$dateExpression as day"),
                DB::raw('SUM(amount) as amount'),
            ])
            ->groupBy(DB::raw($dateExpression))
            ->pluck('amount', 'day');

        $incomeByDay = Schema::hasTable('incomes')
            ? Income::whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->select([
                    DB::raw("$dateExpression as day"),
                    DB::raw('SUM(amount) as amount'),
                ])
                ->groupBy(DB::raw($dateExpression))
                ->pluck('amount', 'day')
            : collect();

        $payrollByDay = Schema::hasTable('payrolls')
            ? Payroll::whereBetween('period_end', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->select([
                    DB::raw("strftime('%Y-%m-%d', period_end) as day"),
                    DB::raw('SUM(total_paid) as amount'),
                ])
                ->groupBy(DB::raw("strftime('%Y-%m-%d', period_end)"))
                ->pluck('amount', 'day')
            : collect();

        $days = [];
        $maxVolume = 1;

        foreach (range(0, 6) as $index) {
            $date = $weekStart->copy()->addDays($index);
            $key = $date->format('Y-m-d');
            $sales = ((float) $salesByDay->get($key, 0)) / 100;
            $income = ((float) $incomeByDay->get($key, 0)) / 100;
            $purchases = ((float) $purchasesByDay->get($key, 0)) / 100;
            $expenses = (((float) $expensesByDay->get($key, 0)) + ((float) $payrollByDay->get($key, 0))) / 100;
            $sales += $income;
            $volume = $sales + $purchases + $expenses;
            $maxVolume = max($maxVolume, $volume);

            $days[] = compact('index', 'date', 'sales', 'purchases', 'expenses', 'volume');
        }

        return array_map(function ($day) use ($maxVolume) {
            $volume = $day['volume'];
            $total = max($volume, 1);

            return [
                'label' => $day['date']->format('D'),
                'sales' => $day['sales'],
                'purchases' => $day['purchases'],
                'expenses' => $day['expenses'],
                'volume' => $volume,
                'height' => $volume > 0 ? round(42 + (($volume / $maxVolume) * 126)) : 18,
                'sales_percent' => $volume > 0 ? round(($day['sales'] / $total) * 100, 2) : 0,
                'purchases_percent' => $volume > 0 ? round(($day['purchases'] / $total) * 100, 2) : 0,
                'expenses_percent' => $volume > 0 ? round(($day['expenses'] / $total) * 100, 2) : 0,
                'is_today' => $day['date']->isToday(),
                'is_off_day' => $day['date']->isFriday(),
                'delay' => $day['index'] * 90,
            ];
        }, $days);
    }


    public function currentMonthChart() {
        abort_if(!request()->ajax(), 404);

        $metrics = app(FinancialMetricsService::class)->summary(
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString()
        );

        return response()->json([
            'sales'     => $metrics['net_sales_amount'],
            'purchases' => $metrics['net_purchases_amount'],
            'expenses'  => $metrics['expenses_amount'],
            'net_result' => $metrics['net_result_amount']
        ]);
    }


    public function salesPurchasesChart() {
        abort_if(!request()->ajax(), 404);

        $sales = $this->salesChartData();
        $purchases = $this->purchasesChartData();

        return response()->json(['sales' => $sales, 'purchases' => $purchases]);
    }


    public function paymentChart() {
        abort_if(!request()->ajax(), 404);

        $dates = collect();
        foreach (range(-11, 0) as $i) {
            $date = Carbon::now()->addMonths($i)->format('m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subYear()->format('Y-m-d');

        $sale_payments = SalePayment::where('date', '>=', $date_range)
            ->where('payment_method', '!=', 'Prepay Balance')
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $sale_return_payments = SaleReturnPayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_payments = PurchasePayment::where('date', '>=', $date_range)
            ->where('payment_method', '!=', 'Prepay Balance')
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $purchase_return_payments = PurchaseReturnPayment::where('date', '>=', $date_range)
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $expenses = Expense::where('date', '>=', $date_range)
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $incomes = Schema::hasTable('incomes')
            ? Income::where('date', '>=', $date_range)
                ->select([
                    DB::raw("strftime('%m-%Y', date) as month"),
                    DB::raw("SUM(amount) as amount")
                ])
                ->groupBy('month')->orderBy('month')
                ->get()->pluck('amount', 'month')
            : collect();

        $payrolls = Schema::hasTable('payrolls')
            ? Payroll::where('period_end', '>=', $date_range)
                ->select([
                    DB::raw("strftime('%m-%Y', period_end) as month"),
                    DB::raw("SUM(total_paid) as amount")
                ])
                ->groupBy('month')->orderBy('month')
                ->get()->pluck('amount', 'month')
            : collect();

        $customer_prepays = \App\Models\PartyPayment::where('date', '>=', $date_range)
            ->where('party_type', 'customer')
            ->where('payment_type', 'prepay')
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $supplier_prepays = \App\Models\PartyPayment::where('date', '>=', $date_range)
            ->where('party_type', 'supplier')
            ->where('payment_type', 'prepay')
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                DB::raw("SUM(amount) as amount")
            ])
            ->groupBy('month')->orderBy('month')
            ->get()->pluck('amount', 'month');

        $payment_received = array_merge_numeric_values($sale_payments, $purchase_return_payments, $customer_prepays->toArray(), $incomes);
        $payment_sent = array_merge_numeric_values($purchase_payments, $sale_return_payments, $expenses, $payrolls, $supplier_prepays->toArray());

        $dates_received = $dates->merge($payment_received);
        $dates_sent = $dates->merge($payment_sent);

        $received_payments = [];
        $sent_payments = [];
        $months = [];

        foreach ($dates_received as $key => $value) {
            $received_payments[] = $value;
            $months[] = $key;
        }

        foreach ($dates_sent as $key => $value) {
            $sent_payments[] = $value;
        }

        return response()->json([
            'payment_sent' => $sent_payments,
            'payment_received' => $received_payments,
            'months' => $months,
        ]);
    }

    public function payableReceivableChart(PartyLedgerService $partyLedger) {
        abort_if(!request()->ajax(), 404);

        return response()->json($partyLedger->monthlyChart());
    }

    public function prepayPayLaterChart() {
        abort_if(!request()->ajax(), 404);

        $dates = collect();
        foreach (range(-11, 0) as $i) {
            $date = Carbon::now()->addMonths($i)->format('m-Y');
            $dates->put($date, ['customer_prepay' => 0, 'supplier_prepay' => 0, 'customer_pay_later' => 0, 'supplier_pay_later' => 0]);
        }

        $date_range = Carbon::today()->subYear()->toDateString();

        \App\Models\PartyPayment::query()
            ->select([
                DB::raw("strftime('%m-%Y', date) as month"),
                'party_type',
                'payment_type',
                DB::raw('SUM(amount) as sum_amount'),
            ])
            ->where('date', '>=', $date_range)
            ->groupBy('month', 'party_type', 'payment_type')
            ->get()
            ->each(function ($row) use ($dates) {
                if (!$dates->has($row->month)) {
                    return;
                }

                $amount = ((float) $row->sum_amount) / 100;
                $data = $dates->get($row->month);

                if ($row->party_type === 'customer' && $row->payment_type === 'prepay') {
                    $data['customer_prepay'] += $amount;
                } elseif ($row->party_type === 'supplier' && $row->payment_type === 'prepay') {
                    $data['supplier_prepay'] += $amount;
                } elseif ($row->party_type === 'customer' && $row->payment_type === 'pay_later') {
                    $data['customer_pay_later'] += $amount;
                } elseif ($row->party_type === 'supplier' && $row->payment_type === 'pay_later') {
                    $data['supplier_pay_later'] += $amount;
                }

                $dates->put($row->month, $data);
            });

        return response()->json([
            'months' => $dates->keys()->values()->all(),
            'customer_prepay' => $dates->pluck('customer_prepay')->values()->all(),
            'supplier_prepay' => $dates->pluck('supplier_prepay')->values()->all(),
            'customer_pay_later' => $dates->pluck('customer_pay_later')->values()->all(),
            'supplier_pay_later' => $dates->pluck('supplier_pay_later')->values()->all(),
        ]);
    }

    public function stockMovementChart() {
        abort_if(!request()->ajax(), 404);

        $dates = collect();
        foreach (range(-11, 0) as $i) {
            $date = Carbon::now()->addMonths($i)->format('m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subYear();

        $stockIn = PurchaseDetail::query()
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', 'Completed')
            ->where('purchases.date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%m-%Y', purchases.date)"))
            ->orderBy(DB::raw("MIN(purchases.date)"))
            ->get([
                DB::raw("strftime('%m-%Y', purchases.date) as date"),
                DB::raw('SUM(purchase_details.quantity) AS quantity'),
            ])
            ->pluck('quantity', 'date');

        $stockOut = SaleDetails::query()
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.status', 'Completed')
            ->where('sales.date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%m-%Y', sales.date)"))
            ->orderBy(DB::raw("MIN(sales.date)"))
            ->get([
                DB::raw("strftime('%m-%Y', sales.date) as date"),
                DB::raw('SUM(sale_details.quantity) AS quantity'),
            ])
            ->pluck('quantity', 'date');

        $days = [];
        $incoming = [];
        $outgoing = [];

        foreach ($dates as $key => $value) {
            $in = (float) $stockIn->get($key, 0);
            $out = (float) $stockOut->get($key, 0);

            $days[] = $key;
            $incoming[] = $in;
            $outgoing[] = $out;
        }

        return response()->json([
            'days' => $days,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ]);
    }

    public function stockMovementWeeklyChart() {
        abort_if(!request()->ajax(), 404);

        $dates = collect();
        foreach (range(-6, 0) as $i) {
            $date = Carbon::now()->addDays($i)->format('d-m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subDays(6);

        $stockIn = PurchaseDetail::query()
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', 'Completed')
            ->where('purchases.date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%d-%m-%Y', purchases.date)"))
            ->orderBy(DB::raw("MIN(purchases.date)"))
            ->get([
                DB::raw("strftime('%d-%m-%Y', purchases.date) as date"),
                DB::raw('SUM(purchase_details.quantity) AS quantity'),
            ])
            ->pluck('quantity', 'date');

        $stockOut = SaleDetails::query()
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.status', 'Completed')
            ->where('sales.date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%d-%m-%Y', sales.date)"))
            ->orderBy(DB::raw("MIN(sales.date)"))
            ->get([
                DB::raw("strftime('%d-%m-%Y', sales.date) as date"),
                DB::raw('SUM(sale_details.quantity) AS quantity'),
            ])
            ->pluck('quantity', 'date');

        $days = [];
        $incoming = [];
        $outgoing = [];

        foreach ($dates as $key => $value) {
            $in = (float) $stockIn->get($key, 0);
            $out = (float) $stockOut->get($key, 0);

            $days[] = $key;
            $incoming[] = $in;
            $outgoing[] = $out;
        }

        return response()->json([
            'days' => $days,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ]);
    }

    public function salesChartData() {
        $dates = collect();
        foreach (range(-6, 0) as $i) {
            $date = Carbon::now()->addDays($i)->format('d-m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subDays(6);

        $sales = Sale::where('status', 'Completed')
            ->where('date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%d-%m-%Y', date)"))
            ->orderBy('date')
            ->get([
                DB::raw(DB::raw("strftime('%d-%m-%Y', date) as date")),
                DB::raw('SUM(total_amount) AS count'),
            ])
            ->pluck('count', 'date');

        $dates = $dates->merge($sales);

        $data = [];
        $days = [];
        foreach ($dates as $key => $value) {
            $data[] = $value / 100;
            $days[] = $key;
        }

        return response()->json(['data' => $data, 'days' => $days]);
    }


    public function purchasesChartData() {
        $dates = collect();
        foreach (range(-6, 0) as $i) {
            $date = Carbon::now()->addDays($i)->format('d-m-Y');
            $dates->put($date, 0);
        }

        $date_range = Carbon::today()->subDays(6);

        $purchases = Purchase::where('status', 'Completed')
            ->where('date', '>=', $date_range)
            ->groupBy(DB::raw("strftime('%d-%m-%Y', date)"))
            ->orderBy('date')
            ->get([
                DB::raw(DB::raw("strftime('%d-%m-%Y', date) as date")),
                DB::raw('SUM(total_amount) AS count'),
            ])
            ->pluck('count', 'date');

        $dates = $dates->merge($purchases);

        $data = [];
        $days = [];
        foreach ($dates as $key => $value) {
            $data[] = $value / 100;
            $days[] = $key;
        }

        return response()->json(['data' => $data, 'days' => $days]);

    }
}
