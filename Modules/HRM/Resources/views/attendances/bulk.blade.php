@extends('layouts.app')

@section('title', 'Bulk Attendance')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendances</a></li>
        <li class="breadcrumb-item active">Bulk</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        @include('includes.neci-page-header', [
            'icon' => 'bi-calendar-range',
            'title' => 'Bulk Attendance',
            'subtitle' => 'Mark attendance for all employees at once'
        ])

        <form action="{{ route('attendances.bulk-store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" required value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->designation ?? '-' }}</td>
                                                <td>
                                                    <select name="attendance[{{ $employee->id }}][status]" class="form-control form-control-sm">
                                                        <option value="present">Present</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="leave">Leave</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="attendance[{{ $employee->id }}][note]" class="form-control form-control-sm" placeholder="Optional note">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group d-flex justify-content-start mt-3 mb-0">
                                <button type="submit" class="btn btn-primary">Save All <i class="bi bi-check-all"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
