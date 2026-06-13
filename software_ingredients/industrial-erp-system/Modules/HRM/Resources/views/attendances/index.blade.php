@extends('layouts.app')

@section('title', 'Attendances')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/datatables.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">HRM</a></li>
        <li class="breadcrumb-item active">Attendances</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('includes.neci-page-header', [
                            'icon' => 'bi-calendar-check',
                            'title' => 'All Attendances',
                            'subtitle' => 'Track employee attendance daily'
                        ])

                        <div class="neci-page-actions">
                            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                                Add Attendance <i class="bi bi-plus"></i>
                            </a>
                            <a href="{{ route('attendances.bulk') }}" class="btn btn-info">
                                Bulk Attendance <i class="bi bi-calendar-range"></i>
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
