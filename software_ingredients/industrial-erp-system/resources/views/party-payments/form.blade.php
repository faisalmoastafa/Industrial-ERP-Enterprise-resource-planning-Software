@php($editing = isset($partyPayment))
<div class="form-row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="date" required value="{{ old('date', $editing ? $partyPayment->date : date('Y-m-d')) }}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Payment Type <span class="text-danger">*</span></label>
            <select class="form-control" name="payment_type" required>
                <option value="prepay" @selected(old('payment_type', $editing ? $partyPayment->payment_type : '') === 'prepay')>Prepay</option>
                <option value="pay_later" @selected(old('payment_type', $editing ? $partyPayment->payment_type : '') === 'pay_later')>Pay Later</option>
            </select>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-lg-4">
        <div class="form-group">
            <label>Party Type <span class="text-danger">*</span></label>
            <select class="form-control" name="party_type" id="partyType" required>
                <option value="customer" @selected(old('party_type', $editing ? $partyPayment->party_type : '') === 'customer')>Customer</option>
                <option value="supplier" @selected(old('party_type', $editing ? $partyPayment->party_type : '') === 'supplier')>Supplier</option>
            </select>
        </div>
    </div>
    <div class="col-lg-8 payment-party-group" data-party-group="customer">
        <div class="form-group">
            <label>Customer</label>
            <select class="form-control" name="customer_id">
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @selected((int) old('customer_id', $editing && $partyPayment->party_type === 'customer' ? $partyPayment->party_id : 0) === $customer->id)>{{ $customer->customer_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-8 payment-party-group" data-party-group="supplier">
        <div class="form-group">
            <label>Supplier</label>
            <select class="form-control" name="supplier_id">
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected((int) old('supplier_id', $editing && $partyPayment->party_type === 'supplier' ? $partyPayment->party_id : 0) === $supplier->id)>{{ $supplier->supplier_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>Amount <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="amount" min="0.01" step="0.01" required value="{{ old('amount', $editing ? $partyPayment->amount : '') }}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Payment Method</label>
            <select class="form-control" name="payment_method">
                @foreach(['Cash', 'Credit Card', 'Bank Transfer', 'Cheque', 'Other'] as $method)
                    <option value="{{ $method }}" @selected(old('payment_method', $editing ? $partyPayment->payment_method : 'Cash') === $method)>{{ $method }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="note" rows="3">{{ old('note', $editing ? $partyPayment->note : '') }}</textarea>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('partyType');
            const groups = document.querySelectorAll('.payment-party-group');
            function syncPartyFields() {
                groups.forEach(group => group.style.display = group.dataset.partyGroup === type.value ? '' : 'none');
            }
            type && type.addEventListener('change', syncPartyFields);
            syncPartyFields();
        });
    </script>
@endpush
