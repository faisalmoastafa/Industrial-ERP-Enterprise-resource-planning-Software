@extends('layouts.app')

@section('title', $reportType . ' Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Reports</li>
        <li class="breadcrumb-item active">{{ $reportType }} Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => $reportType === 'Payable' ? 'bi-arrow-up-right-square' : 'bi-arrow-down-left-square',
            'title' => $reportType . ' Report',
            'subtitle' => 'Review party balances by customer or supplier'
        ])

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ $reportType === 'Payable' ? route('payable-report.index') : route('receivable-report.index') }}" method="GET">
                    <div class="form-row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Party Type</label>
                                <select class="form-control" name="party_type">
                                    <option value="">All Parties</option>
                                    <option value="customer" @selected(request('party_type') === 'customer')>Customer</option>
                                    <option value="supplier" @selected(request('party_type') === 'supplier')>Supplier</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>Name of Customer / Supplier</label>
                                <input type="text" class="form-control" name="party_name" value="{{ request('party_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-start mb-0">
                        <button type="submit" class="btn btn-primary">Filter Report <i class="bi bi-search"></i></button>
                        <a href="{{ $reportType === 'Payable' ? route('payable-report.index') : route('receivable-report.index') }}" class="btn neci-report-reset ml-2">Reset <i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 neci-themed-table" id="party-balances-table">
                        <thead>
                            <tr>
                                <th>Party Type</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Total {{ $reportType }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row['party_type'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['phone'] }}</td>
                                    <td>{{ format_currency(abs($row['balance'])) }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-start mt-3">
                    <div class="border rounded px-3 py-2 font-weight-bold">
                        Total {{ $reportType }} = {{ format_currency($total) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    if ($('#party-balances-table').length) {
        $('#party-balances-table').DataTable({
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
    }
});
</script>
@endpush
