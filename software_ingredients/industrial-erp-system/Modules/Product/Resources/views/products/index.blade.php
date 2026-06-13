@extends('layouts.app')

@section('title', $pageTitle ?? 'Products')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">{{ $pageTitle ?? 'All Products' }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('includes.neci-page-header', [
                            'icon' => ($pageTitle ?? 'All Products') === 'Raw Materials' ? 'bi-building' : 'bi-box-seam',
                            'title' => $pageTitle ?? 'All Products',
                            'subtitle' => $pageDescription ?? 'Review product stock, prices, and inventory details'
                        ])

                        <div class="neci-page-actions">
                            <a href="{{ $createRoute ?? route('products.create') }}" class="btn btn-primary">
                                {{ $createLabel ?? 'Add Product' }} <i class="bi bi-plus"></i>
                            </a>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
@endpush
