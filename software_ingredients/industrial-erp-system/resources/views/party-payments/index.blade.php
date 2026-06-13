@extends('layouts.app')

@section('title', 'All Payments')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('party-payments.index') }}">Payments</a></li>
        <li class="breadcrumb-item active">All Payments</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @include('includes.neci-page-header', [
                    'icon' => 'bi-wallet2',
                    'title' => 'All Payments',
                    'subtitle' => 'Review customer and supplier payment transactions'
                ])

                <div class="neci-page-actions">
                    <a href="{{ route('party-payments.create') }}" class="btn btn-primary">
                        Add Payment <i class="bi bi-plus"></i>
                    </a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#paymentSearchModal">
                        Search Payment <i class="bi bi-search"></i>
                    </button>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 neci-themed-table" id="all-payments-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->reference }}</td>
                                    <td>{{ $payment->formatted_date }}</td>
                                    <td>{{ $payment->party_name }}</td>
                                    <td>{{ $payment->payment_type === 'prepay' ? 'Prepay' : 'Pay Later' }}</td>
                                    <td>{{ format_currency($payment->amount) }}</td>
                                    <td class="text-center">
                                        <div class="neci-table-actions">
                                            @include('includes.neci-action-btn', [
                                                'href' => route('party-payments.show', $payment),
                                                'icon' => 'bi-eye',
                                                'tone' => 'info',
                                                'title' => 'Details',
                                            ])
                                            @include('includes.neci-action-btn', [
                                                'href' => route('party-payments.edit', $payment),
                                                'icon' => 'bi-pencil',
                                                'tone' => 'warning',
                                                'title' => 'Edit',
                                            ])
                                            @include('includes.neci-delete-action', [
                                                'formId' => 'destroy-payment-' . $payment->id,
                                                'action' => route('party-payments.destroy', $payment),
                                            ])
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentSearchModal" tabindex="-1" role="dialog" aria-labelledby="paymentSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('party-payments.index') }}" method="GET" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSearchModalLabel">Search Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control" name="payment_type">
                                    <option value="">Any Type</option>
                                    <option value="prepay" @selected(request('payment_type') === 'prepay')>Prepay</option>
                                    <option value="pay_later" @selected(request('payment_type') === 'pay_later')>Pay Later</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Name of Customer / Supplier</label>
                                <input type="text" class="form-control" name="party_name" value="{{ request('party_name') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Transaction ID</label>
                                <input type="text" class="form-control" name="transaction_id" value="{{ request('transaction_id') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="submit" class="btn btn-primary">Search <i class="bi bi-search"></i></button>
                    <a href="{{ route('party-payments.index') }}" class="btn neci-report-reset">Reset <i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    $('#all-payments-table').DataTable({
        dom: "<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7 mt-2'p>>",
        buttons: [
            { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel-fill"></i> Excel', className: 'btn btn-secondary btn-sm' },
            { extend: 'print', text: '<i class="bi bi-printer-fill"></i> Print', className: 'btn btn-secondary btn-sm' },
            { text: '<i class="bi bi-x-circle"></i> Reset', className: 'btn btn-secondary btn-sm', action: function ( e, dt, node, config ) { dt.search('').draw(); } },
            { text: '<i class="bi bi-arrow-repeat"></i> Reload', className: 'btn btn-secondary btn-sm', action: function ( e, dt, node, config ) { window.location.reload(); } }
        ]
    });
});
</script>
@endpush
