@extends('layouts.app')

@section('title', 'Create Employee')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-person-plus',
            'title' => 'Create Employee',
            'subtitle' => 'Add a new worker to the system'
        ])

        <form id="employee-form" action="{{ route('employees.store') }}" method="POST">
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
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation" placeholder="e.g. Worker, Supervisor">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="salary_type">Salary Type <span class="text-danger">*</span></label>
                                        <select name="salary_type" class="form-control" required>
                                            <option value="monthly">Monthly</option>
                                            <option value="weekly">Weekly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="base_salary">Base Salary <span class="text-danger">*</span></label>
                                        <input id="base_salary" type="text" class="form-control" name="base_salary" required data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="overtime_rate">Overtime Rate (per hour)</label>
                                        <input id="overtime_rate" type="text" class="form-control" name="overtime_rate" value="0" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" rows="3" name="address"></textarea>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Create Employee <i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
