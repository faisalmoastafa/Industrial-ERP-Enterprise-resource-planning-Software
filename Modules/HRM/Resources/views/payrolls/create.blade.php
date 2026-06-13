@extends('layouts.app')

@section('title', 'Create Payroll')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-receipt-cutoff',
            'title' => 'Create Payroll',
            'subtitle' => 'Generate a salary slip for an employee'
        ])

        <form id="payroll-form" action="{{ route('payrolls.store') }}" method="POST">
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
                                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="" selected>Select Employee</option>
                                            @foreach(\Modules\HRM\Entities\Employee::all() as $employee)
                                                <option value="{{ $employee->id }}" data-salary="{{ $employee->base_salary }}">{{ $employee->name }} ({{ $employee->salary_type }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method</label>
                                        <input type="text" class="form-control" name="payment_method" placeholder="e.g. Cash, Bank">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="period_start">Period Start <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="period_start" id="period_start" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="period_end">Period End <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="period_end" id="period_end" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="base_pay">Base Pay <span class="text-danger">*</span></label>
                                        <input id="base_pay" type="text" class="form-control" name="base_pay" required data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="overtime_pay">Overtime Pay</label>
                                        <div class="input-group">
                                            <input id="overtime_pay" type="text" class="form-control" name="overtime_pay" value="0" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                            <div class="input-group-append">
                                                <button type="button" id="fetchOvertimeBtn" class="btn btn-outline-primary" title="Fetch overtime for this period">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Click <i class="bi bi-arrow-repeat"></i> to auto-fill from logged overtime</small>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="bonus_pay">Bonus Pay</label>
                                        <input id="bonus_pay" type="text" class="form-control" name="bonus_pay" value="0" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="deductions">Deductions</label>
                                        <input id="deductions" type="text" class="form-control" name="deductions" value="0" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="advance_deduction">Advance Deduction</label>
                                        <input id="advance_deduction" type="text" class="form-control" name="advance_deduction" value="0" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" rows="3" name="note"></textarea>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Create Payroll <i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
<script>
    document.getElementById('employee_id').addEventListener('change', function() {
        var selected = this.options[this.selectedIndex];
        var salary = selected.getAttribute('data-salary');
        if (salary) {
            document.getElementById('base_pay').value = salary;
        }
    });

    document.getElementById('fetchOvertimeBtn').addEventListener('click', function() {
        var employeeId = document.getElementById('employee_id').value;
        var periodStart = document.getElementById('period_start').value;
        var periodEnd = document.getElementById('period_end').value;

        if (!employeeId || !periodStart || !periodEnd) {
            alert('Please select an employee and enter the period dates first.');
            return;
        }

        var url = '{{ route("payrolls.get-overtime", ["employee" => ":emp", "start" => ":s", "end" => ":e"]) }}'
            .replace(':emp', employeeId)
            .replace(':s', periodStart)
            .replace(':e', periodEnd);

        fetch(url)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                document.getElementById('overtime_pay').value = data.overtime_pay;
            })
            .catch(function() {
                alert('Could not fetch overtime data.');
            });
    });
</script>
@endpush
