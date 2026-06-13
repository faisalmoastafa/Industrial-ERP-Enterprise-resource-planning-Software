@extends('layouts.app')

@section('title', 'Edit Overtime')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('overtimes.index') }}">Overtimes</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-clock-history',
            'title' => 'Edit Overtime',
            'subtitle' => 'Update overtime hours and rate'
        ])

        <form id="overtime-form" action="{{ route('overtimes.update', $overtime) }}" method="POST">
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
                                            @foreach(\Modules\HRM\Entities\Employee::where('status', true)->get() as $employee)
                                                <option {{ $employee->id == $overtime->employee_id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ $overtime->getAttributes()['date'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="hours">Hours <span class="text-danger">*</span></label>
                                        <input type="number" step="0.5" class="form-control" name="hours" required value="{{ $overtime->hours }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="rate_per_hour">Rate Per Hour <span class="text-danger">*</span></label>
                                        <input id="rate_per_hour" type="text" class="form-control" name="rate_per_hour" required value="{{ $overtime->getAttributes()['rate_per_hour'] / 100 }}" data-money data-money-prefix="{{ settings()->currency->symbol }}" data-money-thousands="{{ settings()->currency->thousand_separator }}" data-money-decimal="{{ settings()->currency->decimal_separator }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Update Overtime <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
