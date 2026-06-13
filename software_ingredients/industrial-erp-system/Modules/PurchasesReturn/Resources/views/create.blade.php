@extends('layouts.transaction-form')

@section('title', 'Create Purchase Return')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-arrow-return-left"></i>@endsection
@section('transaction_title', 'Create Purchase Return')
@section('transaction_subtitle', 'Add products, payment details, and notes')
@section('transaction_form_id', 'purchase-return-form')
@section('transaction_form_action', route('purchase-returns.store'))

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Purchase return details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required readonly value="PRRN">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                    <select class="form-control" name="supplier_id" id="supplier_id" required>
                        @foreach(\Modules\People\Entities\Supplier::all() as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ now()->format('Y-m-d') }}">
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
            <livewire:product-cart :cartInstance="'purchase_return'"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Payment & status', 'icon' => 'bi-credit-card'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                    <select class="form-control" name="payment_method" id="payment_method" required>
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="paid_amount">Amount Received <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input id="paid_amount" type="text" class="form-control" name="paid_amount" required data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                        <div class="input-group-append">
                            <button id="getTotalAmount" class="btn btn-primary" type="button" data-money-fill-target="#paid_amount" data-money-fill-source="input[name='total_amount']">
                                <i class="bi bi-check-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control"></textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Create Purchase Return <i class="bi bi-plus"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('purchase-returns.index')])
@endsection
