@extends('layouts.app')

@section('title', isset($batch) ? 'Update Production Batch' : 'Create Production Batch')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('production-batches.index') }}">Manufacturing</a></li>
        <li class="breadcrumb-item active">{{ $batch ?? false ? 'Update Batch' : 'Create Batch' }}</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('utils.alerts')

        @include('includes.neci-page-header', [
            'icon' => 'bi-building',
            'title' => $batch ?? false ? 'Update Production Batch' : 'Create Production Batch',
            'subtitle' => 'Batch costing for copper wire conversion'
        ])

        <form action="{{ $batch ?? false ? route('production-batches.update', $batch) : route('production-batches.store') }}" method="POST" id="production-batch-form">
            @csrf
            @isset($batch)
                @method('PUT')
            @endisset

            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reference">Batch Reference <span class="text-danger">*</span></label>
                                <input type="text" class="form-control neci-readonly-field" id="reference" value="{{ $batch->reference ?? $nextReference }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" id="date" value="{{ old('date', isset($batch) ? $batch->date->toDateString() : now()->toDateString()) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="note">Batch Note</label>
                        <input type="text" class="form-control" name="note" id="note" value="{{ old('note', $batch->note ?? '') }}" placeholder="Machine, operator, supplier lot, or process note">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Raw Material Input</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="input">
                            <i class="bi bi-plus"></i> Add Input
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 neci-themed-table">
                            <thead>
                            <tr>
                                <th style="min-width: 280px;">Product</th>
                                <th style="width: 160px;">Stock in Hand KG</th>
                                <th style="width: 160px;">Input KG</th>
                                <th style="width: 180px;">
                                    Cost / KG
                                    <i class="bi bi-question-circle-fill text-info ml-1"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Purchase price paid per KG. Auto-filled from product cost. You can override it."></i>
                                </th>
                                <th style="width: 70px;"></th>
                            </tr>
                            </thead>
                            <tbody id="input-rows">
                            @php($inputRows = old('inputs', isset($batch) ? $batch->inputs->map(fn($input) => [
                                'product_id' => $input->product_id,
                                'quantity' => $input->quantity,
                                'unit_cost' => $input->unit_cost,
                            ])->toArray() : [['product_id' => '', 'quantity' => '', 'unit_cost' => '']]))
                            @foreach($inputRows as $index => $row)
                            <tr data-row>
                                <td>
                                    <select name="inputs[{{ $index }}][product_id]" class="form-control js-product-select" required>
                                        <option value="">Select raw material</option>
                                        @foreach($rawMaterials as $product)
                                            <option value="{{ $product->id }}" data-cost="{{ $product->product_cost }}" data-stock="{{ $product->product_quantity }}" @selected((string) ($row['product_id'] ?? '') === (string) $product->id)>
                                                {{ $product->product_name }} ({{ $product->product_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control neci-readonly-field js-stock" value="0.000" title="Auto-filled from current product stock" readonly></td>
                                <td><input type="number" name="inputs[{{ $index }}][quantity]" class="form-control js-input-qty" min="0.001" step="0.001" required value="{{ $row['quantity'] ?? '' }}"></td>
                                <td><input type="number" name="inputs[{{ $index }}][unit_cost]" class="form-control js-cost" min="0" step="0.01" value="{{ $row['unit_cost'] ?? '' }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-remove-row>
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Finished Output</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="output">
                            <i class="bi bi-plus"></i> Add Output
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 neci-themed-table">
                            <thead>
                            <tr>
                                <th style="min-width: 280px;">Finished Product</th>
                                <th style="width: 180px;">Wire Size</th>
                                <th style="width: 180px;">Output KG</th>
                                <th style="width: 70px;"></th>
                            </tr>
                            </thead>
                            <tbody id="output-rows">
                            @php($outputRows = old('outputs', isset($batch) ? $batch->outputs->map(fn($output) => [
                                'product_id' => $output->product_id,
                                'quantity' => $output->quantity,
                                'wire_size' => $output->wire_size,
                            ])->toArray() : [['product_id' => '', 'quantity' => '', 'wire_size' => '']]))
                            @foreach($outputRows as $index => $row)
                            <tr data-row>
                                <td>
                                    <select name="outputs[{{ $index }}][product_id]" class="form-control" required>
                                        <option value="">Select finished product</option>
                                        @foreach($finishedProducts as $product)
                                            <option value="{{ $product->id }}" @selected((string) ($row['product_id'] ?? '') === (string) $product->id)>{{ $product->product_name }} ({{ $product->product_code }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="outputs[{{ $index }}][wire_size]" class="form-control" placeholder="15 SWG / 1.5 mm" value="{{ $row['wire_size'] ?? '' }}"></td>
                                <td><input type="number" name="outputs[{{ $index }}][quantity]" class="form-control js-output-qty" min="0.001" step="0.001" required value="{{ $row['quantity'] ?? '' }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-remove-row>
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="text-muted">
                        <span class="mr-3">Input: <strong id="input-total">0.000</strong> KG</span>
                        <span class="mr-3">Output: <strong id="output-total">0.000</strong> KG</span>
                        <span>Wastage: <strong id="wastage-total">0.000</strong> KG</span>
                    </div>
                    <div class="form-group d-flex justify-content-start mt-3 mb-0">
                        <button type="submit" class="btn btn-primary neci-tx-submit">
                            {{ $batch ?? false ? 'Update Production' : 'Create Production' }} <i class="bi bi-plus"></i>
                        </button>
                        @include('includes.neci-tx-cancel', ['href' => route('production-batches.index')])
                    </div>
                </div>
            </div>
        </form>
    </div>

    <template id="input-row-template">
        <tr data-row>
            <td>
                <select name="inputs[__INDEX__][product_id]" class="form-control js-product-select" required>
                    <option value="">Select raw material</option>
                    @foreach($rawMaterials as $product)
                        <option value="{{ $product->id }}" data-cost="{{ $product->product_cost }}" data-stock="{{ $product->product_quantity }}">
                            {{ $product->product_name }} ({{ $product->product_code }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" class="form-control neci-readonly-field js-stock" value="0.000" title="Auto-filled from current product stock" readonly></td>
            <td><input type="number" name="inputs[__INDEX__][quantity]" class="form-control js-input-qty" min="0.001" step="0.001" required></td>
            <td><input type="number" name="inputs[__INDEX__][unit_cost]" class="form-control js-cost" min="0" step="0.01"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-row>
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="output-row-template">
        <tr data-row>
            <td>
                <select name="outputs[__INDEX__][product_id]" class="form-control" required>
                    <option value="">Select finished product</option>
                    @foreach($finishedProducts as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }} ({{ $product->product_code }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="outputs[__INDEX__][wire_size]" class="form-control" placeholder="15 SWG / 1.5 mm"></td>
            <td><input type="number" name="outputs[__INDEX__][quantity]" class="form-control js-output-qty" min="0.001" step="0.001" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" data-remove-row>
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const counters = {
                input: {{ count($inputRows ?? [[]]) }},
                output: {{ count($outputRows ?? [[]]) }}
            };

            function addRow(type) {
                const template = document.getElementById(`${type}-row-template`);
                const target = document.getElementById(`${type}-rows`);
                const index = counters[type]++;
                target.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', index));
                syncTotals();
            }

            function removeRow(button) {
                const row = button.closest('[data-row]');
                const tbody = row.closest('tbody');
                if (tbody.querySelectorAll('[data-row]').length > 1) {
                    row.remove();
                    syncTotals();
                }
            }

            function syncProduct(select) {
                const option = select.selectedOptions[0];
                const row = select.closest('[data-row]');
                const stock = row.querySelector('.js-stock');
                const cost = row.querySelector('.js-cost');

                if (!option || !option.value) {
                    if (stock) stock.value = '0.000';
                    if (cost) cost.value = '';
                    return;
                }

                if (stock) stock.value = parseFloat(option.dataset.stock || 0).toFixed(3);
                if (cost && !cost.value) cost.value = parseFloat(option.dataset.cost || 0).toFixed(2);
            }

            function sum(selector) {
                return Array.from(document.querySelectorAll(selector)).reduce((total, input) => {
                    return total + (parseFloat(input.value || 0) || 0);
                }, 0);
            }

            function syncTotals() {
                const input = sum('.js-input-qty');
                const output = sum('.js-output-qty');
                document.getElementById('input-total').textContent = input.toFixed(3);
                document.getElementById('output-total').textContent = output.toFixed(3);
                document.getElementById('wastage-total').textContent = Math.max(input - output, 0).toFixed(3);
            }

            document.addEventListener('click', function (event) {
                const addButton = event.target.closest('[data-add-row]');
                const removeButton = event.target.closest('[data-remove-row]');

                if (addButton) {
                    addRow(addButton.dataset.addRow);
                }

                if (removeButton) {
                    removeRow(removeButton);
                }
            });

            document.addEventListener('change', function (event) {
                if (event.target.classList.contains('js-product-select')) {
                    syncProduct(event.target);
                }
            });

            document.addEventListener('input', function (event) {
                if (event.target.matches('.js-input-qty, .js-output-qty')) {
                    syncTotals();
                }
            });

            document.querySelectorAll('.js-product-select').forEach(syncProduct);
            syncTotals();
        });
    </script>
@endpush
