<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\DataTables\EmployeesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\HRM\Entities\Employee;

class EmployeeController extends Controller
{

    public function index(EmployeesDataTable $dataTable) {
        abort_if(Gate::denies('access_employees'), 403);

        return $dataTable->render('hrm::employees.index');
    }


    public function create() {
        abort_if(Gate::denies('create_employees'), 403);

        return view('hrm::employees.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_employees'), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'salary_type' => 'required|in:weekly,monthly',
            'base_salary' => 'required|numeric|max:2147483647',
            'overtime_rate' => 'nullable|numeric|max:2147483647',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        Employee::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary,
            'overtime_rate' => $request->overtime_rate ?? 0,
            'address' => $request->address,
            'status' => $request->status ?? true,
        ]);

        toast('Employee Created!', 'success');

        return redirect()->route('employees.index');
    }


    public function edit(Employee $employee) {
        abort_if(Gate::denies('edit_employees'), 403);

        return view('hrm::employees.edit', compact('employee'));
    }


    public function update(Request $request, Employee $employee) {
        abort_if(Gate::denies('edit_employees'), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'salary_type' => 'required|in:weekly,monthly',
            'base_salary' => 'required|numeric|max:2147483647',
            'overtime_rate' => 'nullable|numeric|max:2147483647',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $employee->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'salary_type' => $request->salary_type,
            'base_salary' => $request->base_salary,
            'overtime_rate' => $request->overtime_rate ?? 0,
            'address' => $request->address,
            'status' => $request->status ?? true,
        ]);

        toast('Employee Updated!', 'info');

        return redirect()->route('employees.index');
    }


    public function destroy(Employee $employee) {
        abort_if(Gate::denies('delete_employees'), 403);

        $employee->delete();

        toast('Employee Deleted!', 'warning');

        return redirect()->route('employees.index');
    }

    public function ledger(Employee $employee) {
        abort_if(Gate::denies('access_employees'), 403);

        $payrolls = $employee->payrolls()->orderBy('period_start', 'desc')->get();

        $totalPaid = $payrolls->sum('total_paid');
        $totalOvertime = $payrolls->sum('overtime_pay');
        $totalBonus = $payrolls->sum('bonus_pay');
        $totalDeductions = $payrolls->sum('deductions') + $payrolls->sum('advance_deduction');

        return view('hrm::employees.ledger', compact(
            'employee', 'payrolls', 'totalPaid', 'totalOvertime', 'totalBonus', 'totalDeductions'
        ));
    }
}
