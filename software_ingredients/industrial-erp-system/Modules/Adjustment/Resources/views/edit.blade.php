@extends('layouts.transaction-form')

@section('title', 'Edit Adjustment')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('adjustments.index') }}">Adjustments</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('transaction_icon')<i class="bi bi-sliders"></i>@endsection
@section('transaction_title', 'Edit Adjustment')
@section('transaction_subtitle', 'Update stock adjustments and notes')
@section('transaction_form_id', 'adjustment-form')
@section('transaction_form_action', route('adjustments.update', $adjustment))

@section('transaction_method')
    @method('patch')
@endsection

@section('transaction_content')
    @include('includes.neci-tx-panel', ['title' => 'Adjustment details', 'icon' => 'bi-info-circle'])
        <div class="form-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="reference">Reference <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="reference" required value="{{ $adjustment->getAttributes()['reference'] }}" readonly>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{ $adjustment->getAttributes()['date'] }}">
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
            <livewire:adjustment.product-table :adjustedProducts="$adjustment->adjustedProducts->toArray()"/>
        </div>
    @include('includes.neci-tx-panel-end')

    @include('includes.neci-tx-panel', ['title' => 'Note', 'icon' => 'bi-journal-text'])
        <div class="form-group mb-0">
            <label for="note">Note (If Needed)</label>
            <textarea name="note" id="note" rows="5" class="form-control">{{ $adjustment->note }}</textarea>
        </div>
    @include('includes.neci-tx-panel-end')
@endsection

@section('transaction_footer')
    <button type="submit" class="btn btn-primary neci-tx-submit">
        Update Adjustment <i class="bi bi-check"></i>
    </button>
    @include('includes.neci-tx-cancel', ['href' => route('adjustments.index')])
@endsection
