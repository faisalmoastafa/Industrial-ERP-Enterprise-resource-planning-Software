@extends('layouts.app')

@section('title', 'Create Attendance')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendances</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-calendar-plus',
            'title' => 'Create Attendance',
            'subtitle' => 'Mark an employee as present, absent, or on leave'
        ])

        <form id="attendance-form" action="{{ route('attendances.store') }}" method="POST">
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
                                        <select name="employee_id" class="form-control" required>
                                            <option value="" selected>Select Employee</option>
                                            @foreach(\Modules\HRM\Entities\Employee::all() as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" rows="3" name="note" placeholder="Optional reason if absent/leave"></textarea>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Save Attendance <i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
