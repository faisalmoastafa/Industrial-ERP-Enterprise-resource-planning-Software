<?php

namespace App\Console\Commands;

use App\Models\PartyPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;

class ApplyPartyPrepayToOpenInvoices extends Command
{
    protected $signature = 'party-prepay:apply-open-invoices';

    protected $description = 'Apply available party prepay balance to existing unpaid and partial sale/purchase invoices.';

    public function handle(): int
    {
        $saleCount = 0;
        $purchaseCount = 0;

        DB::transaction(function () use (&$saleCount, &$purchaseCount) {
            Sale::query()
                ->where('due_amount', '>', 0)
                ->orderBy('date')
                ->orderBy('id')
                ->get()
                ->each(function (Sale $sale) use (&$saleCount) {
                    $customer = Customer::find($sale->customer_id);

                    if (!$customer || SalePayment::where('sale_id', $sale->id)->where('payment_method', 'Prepay Balance')->exists()) {
                        return;
                    }

                    $prepayApplied = min((float) $sale->due_amount, $this->availableCustomerPrepay($customer));

                    if ($prepayApplied <= 0) {
                        return;
                    }

                    SalePayment::create([
                        'date' => $sale->date,
                        'reference' => 'INV/'.$sale->reference,
                        'amount' => $prepayApplied,
                        'sale_id' => $sale->id,
                        'payment_method' => 'Prepay Balance',
                    ]);

                    PartyPayment::create([
                        'date' => $sale->date,
                        'reference' => 'INV/'.$sale->reference,
                        'party_type' => 'customer',
                        'party_id' => $customer->id,
                        'party_name' => $customer->customer_name,
                        'payment_type' => 'pay_later',
                        'amount' => $prepayApplied,
                        'payment_method' => 'Prepay Balance',
                        'note' => 'Customer prepay applied to sale '.$sale->reference,
                    ]);

                    $paidAmount = ((float) $sale->paid_amount) + $prepayApplied;
                    $dueAmount = max(0, ((float) $sale->due_amount) - $prepayApplied);

                    $sale->update([
                        'paid_amount' => $paidAmount * 100,
                        'due_amount' => $dueAmount * 100,
                        'payment_status' => $dueAmount <= 0 ? 'Paid' : 'Partial',
                    ]);

                    $saleCount++;
                });

            Purchase::query()
                ->where('due_amount', '>', 0)
                ->orderBy('date')
                ->orderBy('id')
                ->get()
                ->each(function (Purchase $purchase) use (&$purchaseCount) {
                    $supplier = Supplier::find($purchase->supplier_id);

                    if (!$supplier || PurchasePayment::where('purchase_id', $purchase->id)->where('payment_method', 'Prepay Balance')->exists()) {
                        return;
                    }

                    $prepayApplied = min((float) $purchase->due_amount, $this->availableSupplierPrepay($supplier));

                    if ($prepayApplied <= 0) {
                        return;
                    }

                    PurchasePayment::create([
                        'date' => $purchase->date,
                        'reference' => 'INV/'.$purchase->reference,
                        'amount' => $prepayApplied,
                        'purchase_id' => $purchase->id,
                        'payment_method' => 'Prepay Balance',
                    ]);

                    PartyPayment::create([
                        'date' => $purchase->date,
                        'reference' => 'INV/'.$purchase->reference,
                        'party_type' => 'supplier',
                        'party_id' => $supplier->id,
                        'party_name' => $supplier->supplier_name,
                        'payment_type' => 'pay_later',
                        'amount' => $prepayApplied,
                        'payment_method' => 'Prepay Balance',
                        'note' => 'Supplier prepay applied to purchase '.$purchase->reference,
                    ]);

                    $paidAmount = ((float) $purchase->paid_amount) + $prepayApplied;
                    $dueAmount = max(0, ((float) $purchase->due_amount) - $prepayApplied);

                    $purchase->update([
                        'paid_amount' => $paidAmount * 100,
                        'due_amount' => $dueAmount * 100,
                        'payment_status' => $dueAmount <= 0 ? 'Paid' : 'Partial',
                    ]);

                    $purchaseCount++;
                });
        });

        $this->info("Applied prepay to {$saleCount} sale(s) and {$purchaseCount} purchase(s).");

        return self::SUCCESS;
    }

    private function availableCustomerPrepay(Customer $customer): float
    {
        $invoiceDue = ((float) Sale::where('customer_id', $customer->id)->sum('due_amount')) / 100;
        $paymentBalance = PartyPayment::where('party_type', 'customer')
            ->where('party_id', $customer->id)
            ->get()
            ->sum(fn ($payment) => $payment->signedAmount());

        return max(0, -($customer->opening_balance + $invoiceDue + $paymentBalance));
    }

    private function availableSupplierPrepay(Supplier $supplier): float
    {
        $invoiceDue = ((float) Purchase::where('supplier_id', $supplier->id)->sum('due_amount')) / 100;
        $paymentBalance = PartyPayment::where('party_type', 'supplier')
            ->where('party_id', $supplier->id)
            ->get()
            ->sum(fn ($payment) => $payment->signedAmount());

        return max(0, -$supplier->opening_balance - $invoiceDue + $paymentBalance);
    }
}
