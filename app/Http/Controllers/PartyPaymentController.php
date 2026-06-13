<?php

namespace App\Http\Controllers;

use App\Models\PartyPayment;
use App\Services\PartyPaymentSchemaService;
use Illuminate\Http\Request;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturn;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SalePayment;
use Modules\SalesReturn\Entities\SaleReturn;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class PartyPaymentController extends Controller
{
    public function __construct(PartyPaymentSchemaService $schema)
    {
        $schema->ensure();
    }

    public function index(Request $request)
    {
        $payments = PartyPayment::query()
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->when($request->filled('payment_type'), fn ($query) => $query->where('payment_type', $request->payment_type))
            ->when($request->filled('party_name'), fn ($query) => $query->where('party_name', 'like', '%' . $request->party_name . '%'))
            ->when($request->filled('transaction_id'), fn ($query) => $query->where('reference', 'like', '%' . $request->transaction_id . '%'))
            ->latest()
            ->get();

        return view('party-payments.index', compact('payments'));
    }

    public function create()
    {
        return view('party-payments.create', [
            'customers' => Customer::orderBy('customer_name')->get(),
            'suppliers' => Supplier::orderBy('supplier_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        PartyPayment::create($data);

        toast('Payment Entry Created!', 'success');

        return redirect()->route('party-payments.index');
    }

    public function show(PartyPayment $partyPayment)
    {
        return view('party-payments.show', compact('partyPayment'));
    }

    public function edit(PartyPayment $partyPayment)
    {
        return view('party-payments.edit', [
            'partyPayment' => $partyPayment,
            'customers' => Customer::orderBy('customer_name')->get(),
            'suppliers' => Supplier::orderBy('supplier_name')->get(),
        ]);
    }

    public function update(Request $request, PartyPayment $partyPayment)
    {
        $partyPayment->update($this->validatedData($request));

        toast('Payment Entry Updated!', 'info');

        return redirect()->route('party-payments.index');
    }

    public function destroy(PartyPayment $partyPayment)
    {
        $partyPayment->delete();

        toast('Payment Entry Deleted!', 'warning');

        return redirect()->route('party-payments.index');
    }

    public function ledger(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'party_type' => 'nullable|in:customer,supplier',
            'payment_type' => 'nullable|in:prepay,pay_later',
            'party_id' => 'nullable|integer',
        ]);

        $customers = Customer::orderBy('customer_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $party = $this->resolveParty($request->party_type, $request->party_id);
        $entries = collect();
        $openingBalance = 0;
        $total = 0;

        if ($party) {
            $openingBalance = $request->party_type === 'customer'
                ? $party->opening_balance
                : -$party->opening_balance;

            $paymentEntries = PartyPayment::query()
                ->where('party_type', $request->party_type)
                ->where('party_id', $request->party_id)
                ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
                ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
                ->when($request->filled('payment_type'), fn ($query) => $query->where('payment_type', $request->payment_type))
                ->get()
                ->map(function ($entry) {
                    return [
                        'date' => $entry->getRawOriginal('date'),
                        'display_date' => $entry->formatted_date,
                        'name' => $entry->reference.' - '.($entry->payment_type === 'prepay' ? 'Prepay' : 'Pay Later'),
                        'amount' => $entry->signedAmount(),
                    ];
                });

            $invoiceEntries = $request->party_type === 'customer'
                ? $this->customerHistoryEntries($request)
                : $this->supplierHistoryEntries($request);

            $entries = $invoiceEntries
                ->merge($paymentEntries)
                ->sortBy(fn ($entry) => $entry['date'].' '.$entry['name'])
                ->values();

            $total = $openingBalance + $entries->sum('amount');
        }

        return view('party-payments.ledger', compact('customers', 'suppliers', 'party', 'entries', 'openingBalance', 'total'));
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'party_type' => 'required|in:customer,supplier',
            'customer_id' => 'required_if:party_type,customer|nullable|integer',
            'supplier_id' => 'required_if:party_type,supplier|nullable|integer',
            'payment_type' => 'required|in:prepay,pay_later',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        $partyId = $validated['party_type'] === 'customer' ? $validated['customer_id'] : $validated['supplier_id'];
        $party = $this->resolveParty($validated['party_type'], $partyId);

        abort_if(!$party, 404);

        return [
            'date' => $validated['date'],
            'party_type' => $validated['party_type'],
            'party_id' => $party->id,
            'party_name' => $validated['party_type'] === 'customer' ? $party->customer_name : $party->supplier_name,
            'payment_type' => $validated['payment_type'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'] ?? null,
            'note' => $validated['note'] ?? null,
        ];
    }

    private function resolveParty(?string $partyType, $partyId)
    {
        if (!$partyType || !$partyId) {
            return null;
        }

        return $partyType === 'customer'
            ? Customer::find($partyId)
            : Supplier::find($partyId);
    }

    private function customerHistoryEntries(Request $request)
    {
        $sales = Sale::query()
            ->where('customer_id', $request->party_id)
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($sale) => [
                'date' => $sale->date,
                'display_date' => $sale->date,
                'name' => 'INV/'.$sale->reference.' - Sale',
                'amount' => (float) $sale->total_amount,
            ]);

        $salePayments = SalePayment::query()
            ->whereHas('sale', fn ($query) => $query->where('customer_id', $request->party_id))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($payment) => [
                'date' => $payment->getRawOriginal('date'),
                'display_date' => $payment->date,
                'name' => $payment->reference.' - Sale Payment',
                'amount' => -((float) $payment->amount),
            ]);

        $saleReturns = SaleReturn::query()
            ->where('customer_id', $request->party_id)
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($saleReturn) => [
                'date' => $saleReturn->date,
                'display_date' => $saleReturn->date,
                'name' => 'INV/'.$saleReturn->reference.' - Sale Return',
                'amount' => -((float) $saleReturn->total_amount),
            ]);

        $saleReturnPayments = SaleReturnPayment::query()
            ->whereHas('saleReturn', fn ($query) => $query->where('customer_id', $request->party_id))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($payment) => [
                'date' => $payment->getRawOriginal('date'),
                'display_date' => $payment->date,
                'name' => $payment->reference.' - Sale Return Payment',
                'amount' => (float) $payment->amount,
            ]);

        return $sales->merge($salePayments)->merge($saleReturns)->merge($saleReturnPayments);
    }

    private function supplierHistoryEntries(Request $request)
    {
        $purchases = Purchase::query()
            ->where('supplier_id', $request->party_id)
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($purchase) => [
                'date' => $purchase->date,
                'display_date' => $purchase->date,
                'name' => 'INV/'.$purchase->reference.' - Purchase',
                'amount' => -((float) $purchase->total_amount),
            ]);

        $purchasePayments = PurchasePayment::query()
            ->whereHas('purchase', fn ($query) => $query->where('supplier_id', $request->party_id))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($payment) => [
                'date' => $payment->getRawOriginal('date'),
                'display_date' => $payment->date,
                'name' => $payment->reference.' - Purchase Payment',
                'amount' => (float) $payment->amount,
            ]);

        $purchaseReturns = PurchaseReturn::query()
            ->where('supplier_id', $request->party_id)
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($purchaseReturn) => [
                'date' => $purchaseReturn->date,
                'display_date' => $purchaseReturn->date,
                'name' => 'INV/'.$purchaseReturn->reference.' - Purchase Return',
                'amount' => (float) $purchaseReturn->total_amount,
            ]);

        $purchaseReturnPayments = PurchaseReturnPayment::query()
            ->whereHas('purchaseReturn', fn ($query) => $query->where('supplier_id', $request->party_id))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('date', '<=', $request->end_date))
            ->get()
            ->map(fn ($payment) => [
                'date' => $payment->getRawOriginal('date'),
                'display_date' => $payment->date,
                'name' => $payment->reference.' - Purchase Return Payment',
                'amount' => -((float) $payment->amount),
            ]);

        return $purchases->merge($purchasePayments)->merge($purchaseReturns)->merge($purchaseReturnPayments);
    }
}
