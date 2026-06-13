@extends('layouts.transaction-form')

@section('title', 'Edit Sale Return')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-arrow-return-right"></i>@endsection
@section('transaction_title', 'Edit Sale Return')
@section('transaction_subtitle', 'Update products, payment details, and notes')
@section('transaction_form_id', 'sale-return-form')
@section('transaction_form_action', route('sale-returns.update', $sale_return))

@section('transaction_method')
    @method('patch')
@endsection

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Sale return details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required value="{{ $sale_return->reference }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <select class="form-control" name="customer_id" id="customer_id" required>
                        @foreach(\Modules\People\Entities\Customer::all() as $customer)
                            <option {{ $sale_return->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ $sale_return->date }}">
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
            <livewire:product-cart :cartInstance="'sale_return'" :data="$sale_return"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Payment & status', 'icon' => 'bi-credit-card'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" required>
                        <option {{ $sale_return->status == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                        <option {{ $sale_return->status == 'Shipped' ? 'selected' : '' }} value="Shipped">Shipped</option>
                        <option {{ $sale_return->status == 'Completed' ? 'selected' : '' }} value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="payment_method" required value="{{ $sale_return->payment_method }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="paid_amount">Amount Paid <span class="text-danger">*</span></label>
                    <input id="paid_amount" type="text" class="form-control" name="paid_amount" required value="{{ $sale_return->paid_amount }}" readonly data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}" data-money-mask-on-load="true">
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control">{{ $sale_return->note }}</textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Update Sale Return <i class="bi bi-check"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('sale-returns.index')])
@endsection
