@extends('layouts.app')

@section('title', 'Production Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('production-batches.index') }}">Manufacturing</a></li>
        <li class="breadcrumb-item active">Production Report</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'svg',
            'svg' => '<svg class="neci-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;"><path d="M3 21V9l5 3V9l5 3V5h8v16H3z"></path><path d="M7 21v-4h3v4"></path></svg>',
            'title' => 'Production Report',
            'subtitle' => 'Cost, conversion expense, wastage, and yield summary'
        ])

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('production-report.index') }}" method="GET">
                    <div class="form-row align-items-end">
                        <div class="col-lg-4">
                            <div class="form-group mb-lg-0">
                                <label for="date_from">From Date</label>
                                <input type="date" class="form-control" name="date_from" id="date_from" value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-lg-0">
                                <label for="date_to">To Date</label>
                                <input type="date" class="form-control" name="date_to" id="date_to" value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <button type="submit" class="btn btn-primary">
                                Filter Report <i class="bi bi-funnel"></i>
                            </button>
                            <a href="{{ route('production-report.index') }}" class="btn neci-report-reset">
                                Reset <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Completed Batches</div>
                        <div class="h4 mb-0">{{ $summary['completed_batches'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Input KG</div>
                        <div class="h4 mb-0">{{ number_format($summary['input_weight'], 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Output KG</div>
                        <div class="h4 mb-0">{{ number_format($summary['output_weight'], 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Wastage KG</div>
                        <div class="h4 mb-0">{{ number_format($summary['wastage_weight'], 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Yield</div>
                        <div class="h4 mb-0">{{ number_format($summary['yield_percent'], 2) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Cost</div>
                        <div class="h4 mb-0">{{ format_currency($summary['total_cost']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 neci-themed-table" id="production-report-table">
                        <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th class="text-right">Input KG</th>
                            <th class="text-right">Output KG</th>
                            <th class="text-right">Wastage KG</th>
                            <th class="text-right">Raw Cost</th>
                            <th class="text-right">Total Cost</th>
                            <th class="text-right">Cost / KG</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td><a href="{{ route('production-batches.show', $batch) }}">{{ $batch->reference }}</a></td>
                                <td>{{ $batch->date->format('d M, Y') }}</td>
                                <td class="text-right">{{ number_format($batch->input_weight, 3) }}</td>
                                <td class="text-right">{{ number_format($batch->output_weight, 3) }}</td>
                                <td class="text-right">{{ number_format($batch->wastage_weight, 3) }}</td>
                                <td class="text-right">{{ format_currency($batch->raw_material_cost) }}</td>
                                <td class="text-right">{{ format_currency($batch->total_cost) }}</td>
                                <td class="text-right">{{ format_currency($batch->cost_per_output_kg) }}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    $('#production-report-table').DataTable({
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
