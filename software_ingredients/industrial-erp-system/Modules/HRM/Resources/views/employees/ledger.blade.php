@extends('layouts.app')

@section('title', 'Employee Ledger')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active">Ledger</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-person-badge',
            'title' => $employee->name,
            'subtitle' => 'Payment history & ledger'
        ])

        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <tr>
                            <th style="width: 220px;">Name</th>
                            <td>{{ $employee->name }}</td>
                            <th style="width: 220px;">Phone</th>
                            <td>{{ $employee->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td>{{ $employee->designation ?? 'N/A' }}</td>
                            <th>Salary Type</th>
                            <td>{{ ucfirst($employee->salary_type) }}</td>
                        </tr>
                        <tr>
                            <th>Base Salary</th>
                            <td>{{ format_currency($employee->base_salary) }}</td>
                            <th>Overtime Rate</th>
                            <td>{{ format_currency($employee->overtime_rate) }}/hr</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <h5 class="text-muted mb-1">Total Payrolls</h5>
                        <h3 class="mb-0">{{ $payrolls->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <h5 class="text-muted mb-1">Total Paid</h5>
                        <h3 class="mb-0 text-success">{{ format_currency($totalPaid) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <h5 class="text-muted mb-1">Total Overtime</h5>
                        <h3 class="mb-0 text-info">{{ format_currency($totalOvertime) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <h5 class="text-muted mb-1">Total Deductions</h5>
                        <h3 class="mb-0 text-danger">{{ format_currency($totalDeductions) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Payroll History</h5>
            </div>
            <div class="card-body">
                @if($payrolls->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Period</th>
                                    <th class="text-right">Base Pay</th>
                                    <th class="text-right">Overtime</th>
                                    <th class="text-right">Bonus</th>
                                    <th class="text-right">Deductions</th>
                                    <th class="text-right">Adv. Deduction</th>
                                    <th class="text-right">Total Paid</th>
                                    <th>Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        <td>{{ $payroll->period_start }} &mdash; {{ $payroll->period_end }}</td>
                                        <td class="text-right">{{ format_currency($payroll->base_pay) }}</td>
                                        <td class="text-right">{{ format_currency($payroll->overtime_pay) }}</td>
                                        <td class="text-right">{{ format_currency($payroll->bonus_pay) }}</td>
                                        <td class="text-right">{{ format_currency($payroll->deductions) }}</td>
                                        <td class="text-right">{{ format_currency($payroll->advance_deduction) }}</td>
                                        <td class="text-right"><strong>{{ format_currency($payroll->total_paid) }}</strong></td>
                                        <td>{{ $payroll->payment_method ?: 'N/A' }}</td>
                                        <td class="neci-table-actions">
                                            @include('includes.neci-action-btn', [
                                                'href' => route('payrolls.show', $payroll->id),
                                                'icon' => 'bi-eye',
                                                'tone' => 'info',
                                                'title' => 'View',
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <th>Totals</th>
                                    <th class="text-right">{{ format_currency($payrolls->sum('base_pay')) }}</th>
                                    <th class="text-right">{{ format_currency($totalOvertime) }}</th>
                                    <th class="text-right">{{ format_currency($totalBonus) }}</th>
                                    <th class="text-right">{{ format_currency($payrolls->sum('deductions')) }}</th>
                                    <th class="text-right">{{ format_currency($payrolls->sum('advance_deduction')) }}</th>
                                    <th class="text-right">{{ format_currency($totalPaid) }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No payroll records found for this employee.</p>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Employees
            </a>
            <a href="{{ route('payrolls.create', ['employee_id' => $employee->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Payroll
            </a>
        </div>
    </div>
@endsection
