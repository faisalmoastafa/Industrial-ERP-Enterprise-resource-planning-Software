@extends('layouts.app')

@section('title', 'Payment Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('party-payments.index') }}">Payments</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt',
            'title' => 'Payment Details',
            'subtitle' => 'Review transaction details for this payment entry'
        ])

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tr><th>Transaction ID</th><td>{{ $partyPayment->reference }}</td></tr>
                        <tr><th>Date</th><td>{{ $partyPayment->formatted_date }}</td></tr>
                        <tr><th>Party</th><td>{{ ucfirst($partyPayment->party_type) }} - {{ $partyPayment->party_name }}</td></tr>
                        <tr><th>Payment Type</th><td>{{ $partyPayment->payment_type === 'prepay' ? 'Prepay' : 'Pay Later' }}</td></tr>
                        <tr><th>Amount</th><td>{{ format_currency($partyPayment->amount) }}</td></tr>
                        <tr><th>Payment Method</th><td>{{ $partyPayment->payment_method }}</td></tr>
                        <tr><th>Note</th><td>{{ $partyPayment->note ?: '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
