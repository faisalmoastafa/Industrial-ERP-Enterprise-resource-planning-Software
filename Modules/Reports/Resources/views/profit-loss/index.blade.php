@extends('layouts.app')

@section('title', 'Profit / Loss Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">Profit Loss Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-graph-up-arrow',
            'svg' => '<svg class="neci-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
            'title' => 'Profit / Loss Report',
            'subtitle' => 'Review profit, loss, revenue, cost, and expense summary'
        ])

        <livewire:reports.profit-loss-report/>
    </div>
@endsection
