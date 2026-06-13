@extends('layouts.app')

@section('title', 'Payroll Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt-cutoff',
            'title' => 'Payroll Details',
            'subtitle' => 'Salary slip summary for employee'
        ])

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <tr>
                            <th style="width: 220px;">Employee</th>
                            <td>{{ $payroll->employee->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Period</th>
                            <td>{{ $payroll->period_start }} to {{ $payroll->period_end }}</td>
                        </tr>
                        <tr>
                            <th>Base Pay</th>
                            <td>{{ format_currency($payroll->base_pay) }}</td>
                        </tr>
                        <tr>
                            <th>Overtime Pay</th>
                            <td>{{ format_currency($payroll->overtime_pay) }}</td>
                        </tr>
                        <tr>
                            <th>Bonus Pay</th>
                            <td>{{ format_currency($payroll->bonus_pay) }}</td>
                        </tr>
                        <tr>
                            <th>Deductions</th>
                            <td>{{ format_currency($payroll->deductions) }}</td>
                        </tr>
                        <tr>
                            <th>Advance Deduction</th>
                            <td>{{ format_currency($payroll->advance_deduction) }}</td>
                        </tr>
                        <tr>
                            <th>Total Paid</th>
                            <td><strong>{{ format_currency($payroll->total_paid) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ $payroll->payment_method ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td>{{ $payroll->note ?: 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
