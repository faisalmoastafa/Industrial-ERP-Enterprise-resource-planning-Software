@extends('layouts.app')

@section('title', 'Production Batch Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('production-batches.index') }}">Production Batches</a></li>
        <li class="breadcrumb-item active">{{ $batch->reference }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-building',
            'title' => 'Production Batch Details',
            'subtitle' => 'Review material input, finished output, wastage, and conversion cost'
        ])

        @include('utils.alerts')

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-1">{{ $batch->reference }}</h4>
                        <div class="text-muted">{{ $batch->date->format('d M, Y') }} · {{ $batch->status }}</div>
                    </div>
                    <div class="text-md-right">
                        <div class="h5 mb-1">{{ format_currency($batch->total_cost) }}</div>
                        <div class="text-muted">Cost / KG: {{ format_currency($batch->cost_per_output_kg) }}</div>
                    </div>
                </div>
                @if($batch->note)
                    <hr>
                    <div>{{ $batch->note }}</div>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Input KG</div>
                        <div class="h4 mb-0">{{ number_format($batch->input_weight, 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Output KG</div>
                        <div class="h4 mb-0">{{ number_format($batch->output_weight, 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Wastage KG</div>
                        <div class="h4 mb-0">{{ number_format($batch->wastage_weight, 3) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">Conversion Cost</div>
                        <div class="h4 mb-0">{{ format_currency($batch->conversion_cost) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Raw Material Input</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 neci-themed-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-right">KG</th>
                                    <th class="text-right">Cost / KG</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($batch->inputs as $input)
                                    <tr>
                                        <td>{{ $input->product_name }} <span class="text-muted">{{ $input->product_code }}</span></td>
                                        <td class="text-right">{{ number_format($input->quantity, 3) }}</td>
                                        <td class="text-right">{{ format_currency($input->unit_cost) }}</td>
                                        <td class="text-right">{{ format_currency($input->sub_total) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Finished Output</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 neci-themed-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th class="text-right">KG</th>
                                    <th class="text-right">Cost / KG</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($batch->outputs as $output)
                                    <tr>
                                        <td>{{ $output->product_name }} <span class="text-muted">{{ $output->product_code }}</span></td>
                                        <td>{{ $output->wire_size ?: '-' }}</td>
                                        <td class="text-right">{{ number_format($output->quantity, 3) }}</td>
                                        <td class="text-right">{{ format_currency($output->unit_cost) }}</td>
                                        <td class="text-right">{{ format_currency($output->sub_total) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Conversion Expenses</h5>
                    @can('create_expenses')
                        <a href="{{ route('conversion-expenses.create', ['batch_id' => $batch->id]) }}" class="btn btn-sm btn-primary">
                            Add Conversion Expense <i class="bi bi-plus"></i>
                        </a>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 neci-themed-table">
                        <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Expense</th>
                            <th>Note</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($batch->expenses as $expense)
                            <tr>
                                <td>{{ $expense->reference ?: '-' }}</td>
                                <td>{{ $expense->date ? $expense->date->format('d M, Y') : '-' }}</td>
                                <td>{{ $expense->name }}</td>
                                <td>{{ $expense->note ?: '-' }}</td>
                                <td class="text-right">{{ format_currency($expense->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No conversion expenses recorded.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
