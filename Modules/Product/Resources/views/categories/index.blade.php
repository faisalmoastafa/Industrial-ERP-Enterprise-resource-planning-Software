@extends('layouts.app')

@section('title', 'Product Categories')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Categories</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
                <div class="card">
                    <div class="card-body">
                        @include('includes.neci-page-header', [
                            'icon' => 'bi-tags',
                            'title' => 'Product Categories',
                            'subtitle' => 'Organize products with reusable category groups'
                        ])

                        <div class="neci-page-actions">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryCreateModal">
                                Add Category <i class="bi bi-plus"></i>
                            </button>
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

    <!-- Create Modal -->
    @include('product::includes.category-modal')
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
@endpush
