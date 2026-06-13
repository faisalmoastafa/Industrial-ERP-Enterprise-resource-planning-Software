@extends('layouts.transaction-form')

@section('title', 'Edit Quotation')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-file-earmark-text"></i>@endsection
@section('transaction_title', 'Edit Quotation')
@section('transaction_subtitle', 'Update products and quotation status')
@section('transaction_form_id', 'quotation-form')
@section('transaction_form_action', route('quotations.update', $quotation))

@section('transaction_method')
    @method('patch')
@endsection

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Quotation details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required value="{{ $quotation->reference }}" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <select class="form-control" name="customer_id" id="customer_id" required>
                        @foreach(\Modules\People\Entities\Customer::all() as $customer)
                            <option {{ $quotation->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ $quotation->getAttributes()['date'] }}">
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
            <livewire:product-cart :cartInstance="'quotation'" :data="$quotation"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Status', 'icon' => 'bi-flag'])
        <div class="form-row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" required>
                        <option {{ $quotation->status == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                        <option {{ $quotation->status == 'Sent' ? 'selected' : '' }} value="Sent">Sent</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control">{{ $quotation->note }}</textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Update Quotation <i class="bi bi-check"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('quotations.index')])
@endsection

@push('page_scripts')

@endpush
