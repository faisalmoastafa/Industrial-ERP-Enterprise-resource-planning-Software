@extends('layouts.app')

@section('title', 'Payments Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">Payments Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-wallet2',
            'title' => 'Payments Report',
            'subtitle' => 'Review payment transactions by date, party, type, and amount'
        ])

        <livewire:reports.payments-report/>
    </div>
@endsection
