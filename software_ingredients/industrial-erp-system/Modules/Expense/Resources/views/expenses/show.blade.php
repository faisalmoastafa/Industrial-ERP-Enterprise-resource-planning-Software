@extends('layouts.app')

@section('title', 'Expense Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt',
            'title' => 'Expense Details',
            'subtitle' => 'Review expense category, amount, date, and note'
        ])

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <tr>
                            <th style="width: 220px;">Reference</th>
                            <td>{{ $expense->reference }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $expense->date }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $expense->category->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ format_currency($expense->amount) }}</td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td>{{ $expense->details ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
