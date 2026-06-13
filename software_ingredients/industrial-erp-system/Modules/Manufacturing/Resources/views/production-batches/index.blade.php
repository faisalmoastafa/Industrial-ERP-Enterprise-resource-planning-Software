@extends('layouts.app')

@section('title', 'Production Batches')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('production-batches.index') }}">Manufacturing</a></li>
        <li class="breadcrumb-item active">All Batches</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('utils.alerts')

        <div class="card">
            <div class="card-body">
                @include('includes.neci-page-header', [
                    'icon' => 'bi-building',
                    'title' => 'All Batches',
                    'subtitle' => 'Production entries and stock movements for enamel wire batches'
                ])

                <div class="neci-page-actions">
                    @can('create_production_batches')
                        <a href="{{ route('production-batches.create') }}" class="btn btn-primary">
                            Create Batch <i class="bi bi-plus"></i>
                        </a>
                    @endcan
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 neci-themed-table" id="production-batches-table">
                        <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-right">Input KG</th>
                            <th class="text-right">Output KG</th>
                            <th class="text-right">Wastage KG</th>
                            <th class="text-right">Cost / KG</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td class="font-weight-bold">{{ $batch->reference }}</td>
                                <td>{{ $batch->date->format('d M, Y') }}</td>
                                <td><span class="badge badge-success">{{ $batch->status }}</span></td>
                                <td class="text-right">{{ number_format($batch->input_weight, 3) }}</td>
                                <td class="text-right">{{ number_format($batch->output_weight, 3) }}</td>
                                <td class="text-right">{{ number_format($batch->wastage_weight, 3) }}</td>
                                <td class="text-right">{{ format_currency($batch->cost_per_output_kg) }}</td>
                                <td class="text-center">
                                    <div class="neci-table-actions">
                                        @can('show_production_batches')
                                            @include('includes.neci-action-btn', [
                                                'href' => route('production-batches.show', $batch),
                                                'icon' => 'bi-eye',
                                                'tone' => 'info',
                                                'title' => 'Details',
                                            ])
                                        @endcan
                                        @can('access_manufacturing')
                                            @include('includes.neci-action-btn', [
                                                'href' => route('production-batches.edit', $batch),
                                                'icon' => 'bi-pencil',
                                                'tone' => 'warning',
                                                'title' => 'Edit',
                                            ])
                                        @endcan
                                        @can('delete_production_batches')
                                            @include('includes.neci-delete-action', [
                                                'formId' => 'destroy-production-batch-' . $batch->id,
                                                'action' => route('production-batches.destroy', $batch),
                                            ])
                                        @endcan
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
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    $('#production-batches-table').DataTable({
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
