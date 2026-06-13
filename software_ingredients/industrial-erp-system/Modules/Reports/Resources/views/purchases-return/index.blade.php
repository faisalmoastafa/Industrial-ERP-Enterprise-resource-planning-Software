@extends('layouts.app')

@section('title', 'Purchases Return Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">Purchases Return Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-arrow-return-left',
            'title' => 'Purchases Return Report',
            'subtitle' => 'Review returned purchases by supplier, date, status, and amount'
        ])

        <livewire:reports.purchases-return-report :suppliers="\Modules\People\Entities\Supplier::all()"/>
    </div>
@endsection
