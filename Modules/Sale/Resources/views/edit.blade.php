@extends('layouts.transaction-form')

@section('title', 'Edit Sale')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-receipt"></i>@endsection
@section('transaction_title', 'Edit Sale')
@section('transaction_subtitle', 'Update products, payment details, and notes')
@section('transaction_form_id', 'sale-form')
@section('transaction_form_action', route('sales.update', $sale))

@section('transaction_method')
    @method('patch')
@endsection

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Sale details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required value="{{ $sale->reference }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <select class="form-control" name="customer_id" id="customer_id" required>
                        @foreach(\Modules\People\Entities\Customer::all() as $customer)
                            <option {{ $sale->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ $sale->date }}">
                </div>
            </div>
        </div>
        <div class="neci-tx-search-wrap">
            <label class="neci-tx-search-label"><i class="bi bi-search"></i> Search product to add</label>
            <livewire:search-product product-type="finished"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Products', 'icon' => 'bi-cart3', 'class' => 'neci-tx-panel--products'])
        <div class="neci-tx-cart-wrap">
            <livewire:product-cart :cartInstance="'sale'" :data="$sale"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Payment & status', 'icon' => 'bi-credit-card'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" required>
                        <option {{ $sale->status == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                        <option {{ $sale->status == 'Shipped' ? 'selected' : '' }} value="Shipped">Shipped</option>
                        <option {{ $sale->status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="payment_method" required value="{{ $sale->payment_method }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="paid_amount">Amount Received <span class="text-danger">*</span></label>
                    <input id="paid_amount" type="text" class="form-control" name="paid_amount" required value="{{ $sale->paid_amount }}" {{ auth()->user()->can('edit_sale_amount_received') ? '' : 'readonly' }} data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control">{{ $sale->note }}</textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Update Sale <i class="bi bi-check"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('sales.index')])
@endsection
