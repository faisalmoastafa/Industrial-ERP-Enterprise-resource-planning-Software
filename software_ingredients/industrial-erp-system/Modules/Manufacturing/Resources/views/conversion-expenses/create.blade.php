@extends('layouts.app')

@section('title', 'Create Conversion Expense')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">Conversion Expense</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-cash-coin',
            'title' => 'Create Conversion Expense',
            'subtitle' => 'Add labor, electricity, enamel, chemical, or other costs after production'
        ])

        <form id="conversion-expense-form" action="{{ route('conversion-expenses.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="reference">Reference <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control neci-readonly-field" id="reference" value="{{ $nextReference }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" id="date" required value="{{ old('date', now()->toDateString()) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="production_batch_id">Production Batch <span class="text-danger">*</span></label>
                                        <select name="production_batch_id" id="production_batch_id" class="form-control" required>
                                            <option value="">Select Batch</option>
                                            @foreach($batches as $batch)
                                                <option value="{{ $batch->id }}" @selected((string) old('production_batch_id', $selectedBatchId ?? '') === (string) $batch->id)>
                                                    {{ $batch->reference }} - {{ $batch->date->format('d M, Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Expense Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" required value="{{ old('name') }}" placeholder="Labor, electricity, enamel, chemical">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input id="amount" type="text" class="form-control" name="amount" required value="{{ old('amount') }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <input type="text" class="form-control" name="note" id="note" value="{{ old('note') }}" placeholder="Machine, operator, shift, or reason">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary neci-tx-submit">
                                    Create Conversion Expense <i class="bi bi-plus"></i>
                                </button>
                                @include('includes.neci-tx-cancel', ['href' => route('production-batches.index')])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
