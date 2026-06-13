@extends('layouts.app')

@section('title', 'Print Barcode')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Print Barcode</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-upc-scan',
            'title' => 'Print Barcode',
            'subtitle' => 'Search products and generate printable barcode labels'
        ])

        <div class="row">
            <div class="col-12">
                <livewire:search-product/>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>NOTE: Product Code must be a number to generate barcodes!</strong>
                </div>
            </div>
            <div class="col-md-12">
                <livewire:barcode.product-table/>
            </div>
        </div>
    </div>
@endsection
