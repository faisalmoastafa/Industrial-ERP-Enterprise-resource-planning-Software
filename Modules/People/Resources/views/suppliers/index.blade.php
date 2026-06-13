@extends('layouts.app')

@section('title', 'Suppliers')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Parties</a></li>
        <li class="breadcrumb-item active">Suppliers</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('includes.neci-page-header', [
                            'icon' => 'bi-shop',
                            'title' => 'Suppliers',
                            'subtitle' => 'Review supplier profiles, balances, and contact details'
                        ])

                        <div class="neci-page-actions">
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                Add Supplier <i class="bi bi-plus"></i>
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
