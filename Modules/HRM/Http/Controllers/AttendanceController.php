<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\DataTables\AttendancesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\HRM\Entities\Attendance;
use Modules\HRM\Entities\Employee;

class AttendanceController extends Controller
{

    public function index(AttendancesDataTable $dataTable) {
        abort_if(Gate::denies('access_attendances'), 403);

        return $dataTable->render('hrm::attendances.index');
    }


    public function create() {
        abort_if(Gate::denies('access_attendances'), 403);

        return view('hrm::attendances.create');
    }

    public function bulk() {
        abort_if(Gate::denies('access_attendances'), 403);

        $employees = Employee::where('status', true)->get();

        return view('hrm::attendances.bulk', compact('employees'));
    }

    public function bulkStore(Request $request) {
        abort_if(Gate::denies('access_attendances'), 403);

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,leave',
            'attendance.*.note' => 'nullable|string|max:1000',
        ]);

        foreach ($request->attendance as $employeeId => $data) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                ]
            );
        }

        toast('Bulk Attendance Saved!', 'success');

        return redirect()->route('attendances.index');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_attendances'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave',
            'note' => 'nullable|string|max:1000',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
                'note' => $request->note,
            ]
        );

        toast('Attendance Saved!', 'success');

        return redirect()->route('attendances.index');
    }


    public function edit(Attendance $attendance) {
        abort_if(Gate::denies('access_attendances'), 403);

        return view('hrm::attendances.edit', compact('attendance'));
    }


    public function update(Request $request, Attendance $attendance) {
        abort_if(Gate::denies('access_attendances'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave',
            'note' => 'nullable|string|max:1000',
        ]);

        $attendance->update([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'status' => $request->status,
            'note' => $request->note,
        ]);

        toast('Attendance Updated!', 'info');

        return redirect()->route('attendances.index');
    }


    public function destroy(Attendance $attendance) {
        abort_if(Gate::denies('access_attendances'), 403);

        $attendance->delete();

        toast('Attendance Deleted!', 'warning');

        return redirect()->route('attendances.index');
    }
}
