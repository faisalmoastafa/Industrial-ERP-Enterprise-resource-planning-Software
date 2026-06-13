@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt-cutoff',
            'title' => 'Edit Payroll',
            'subtitle' => 'Update salary slip details'
        ])

        <form id="payroll-form" action="{{ route('payrolls.update', $payroll) }}" method="POST">
            @csrf
            @method('patch')
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
                                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                        <select name="employee_id" class="form-control" required>
                                            @foreach(\Modules\HRM\Entities\Employee::all() as $employee)
                                                <option {{ $employee->id == $payroll->employee_id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method</label>
                                        <input type="text" class="form-control" name="payment_method" value="{{ $payroll->payment_method }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="period_start">Period Start <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="period_start" required value="{{ $payroll->getAttributes()['period_start'] }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="period_end">Period End <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="period_end" required value="{{ $payroll->getAttributes()['period_end'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="base_pay">Base Pay <span class="text-danger">*</span></label>
                                        <input id="base_pay" type="text" class="form-control" name="base_pay" required value="{{ $payroll->getAttributes()['base_pay'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="overtime_pay">Overtime Pay</label>
                                        <input id="overtime_pay" type="text" class="form-control" name="overtime_pay" value="{{ $payroll->getAttributes()['overtime_pay'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="bonus_pay">Bonus Pay</label>
                                        <input id="bonus_pay" type="text" class="form-control" name="bonus_pay" value="{{ $payroll->getAttributes()['bonus_pay'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="deductions">Deductions</label>
                                        <input id="deductions" type="text" class="form-control" name="deductions" value="{{ $payroll->getAttributes()['deductions'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="advance_deduction">Advance Deduction</label>
                                        <input id="advance_deduction" type="text" class="form-control" name="advance_deduction" value="{{ $payroll->getAttributes()['advance_deduction'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" rows="3" name="note">{{ $payroll->note }}</textarea>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Update Payroll <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
