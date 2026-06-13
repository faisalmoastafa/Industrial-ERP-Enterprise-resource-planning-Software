@extends('layouts.transaction-form')

@section('title', 'Edit Purchase Return')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-arrow-return-left"></i>@endsection
@section('transaction_title', 'Edit Purchase Return')
@section('transaction_subtitle', 'Update products, payment details, and notes')
@section('transaction_form_id', 'purchase-return-form')
@section('transaction_form_action', route('purchase-returns.update', $purchase_return))

@section('transaction_method')
    @method('patch')
@endsection

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Purchase return details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required value="{{ $purchase_return->reference }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                    <select class="form-control" name="supplier_id" id="supplier_id" required>
                        @foreach(\Modules\People\Entities\Supplier::all() as $supplier)
                            <option {{ $purchase_return->supplier_id == $supplier->id ? 'selected' : '' }} value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ $purchase_return->date }}">
                </div>
            </div>
        </div>
        <div class="neci-tx-search-wrap">
            <label class="neci-tx-search-label"><i class="bi bi-search"></i> Search product to add</label>
            <livewire:search-product/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Products', 'icon' => 'bi-cart3', 'class' => 'neci-tx-panel--products'])
        <div class="neci-tx-cart-wrap">
            <livewire:product-cart :cartInstance="'purchase_return'" :data="$purchase_return"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Payment & status', 'icon' => 'bi-credit-card'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" required>
                        <option {{ $purchase_return->status == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                        <option {{ $purchase_return->status == 'Shipped' ? 'selected' : '' }} value="Shipped">Shipped</option>
                        <option {{ $purchase_return->status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="payment_method" required value="{{ $purchase_return->payment_method }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="paid_amount">Amount Paid <span class="text-danger">*</span></label>
                    <input id="paid_amount" type="text" class="form-control" name="paid_amount" required value="{{ $purchase_return->paid_amount }}" readonly data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control">{{ $purchase_return->note }}</textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Update Purchase Return <i class="bi bi-check"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('purchase-returns.index')])
@endsection
