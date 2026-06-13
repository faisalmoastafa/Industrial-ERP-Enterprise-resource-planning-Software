@extends('layouts.app')

@section('title', 'Sales Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">Sales Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt',
            'title' => 'Sales Report',
            'subtitle' => 'Review sales records by customer, date, status, and amount'
        ])

        <livewire:reports.sales-report :customers="\Modules\People\Entities\Customer::all()"/>
    </div>
@endsection
