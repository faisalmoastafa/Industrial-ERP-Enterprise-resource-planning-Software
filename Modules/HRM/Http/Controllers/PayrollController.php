<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\DataTables\PayrollsDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\HRM\Entities\Payroll;
use Modules\HRM\Entities\Employee;
use Modules\HRM\Entities\Overtime;
use Modules\HRM\Entities\Bonus;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{

    public function index(PayrollsDataTable $dataTable) {
        abort_if(Gate::denies('access_payrolls'), 403);

        return $dataTable->render('hrm::payrolls.index');
    }


    public function create() {
        abort_if(Gate::denies('create_payrolls'), 403);

        return view('hrm::payrolls.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_payrolls'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'base_pay' => 'required|numeric|max:2147483647',
            'overtime_pay' => 'nullable|numeric|max:2147483647',
            'bonus_pay' => 'nullable|numeric|max:2147483647',
            'deductions' => 'nullable|numeric|max:2147483647',
            'advance_deduction' => 'nullable|numeric|max:2147483647',
            'payment_method' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        $basePay = $request->base_pay ?? 0;
        $overtimePay = $request->overtime_pay ?? 0;
        $bonusPay = $request->bonus_pay ?? 0;
        $deductions = $request->deductions ?? 0;
        $advanceDeduction = $request->advance_deduction ?? 0;

        $totalPaid = $basePay + $overtimePay + $bonusPay - $deductions - $advanceDeduction;

        Payroll::create([
            'employee_id' => $request->employee_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_pay' => $basePay,
            'overtime_pay' => $overtimePay,
            'bonus_pay' => $bonusPay,
            'deductions' => $deductions,
            'advance_deduction' => $advanceDeduction,
            'total_paid' => max(0, $totalPaid),
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        toast('Payroll Created!', 'success');

        return redirect()->route('payrolls.index');
    }


    public function show(Payroll $payroll) {
        abort_if(Gate::denies('access_payrolls'), 403);

        $payroll->load('employee');

        return view('hrm::payrolls.show', compact('payroll'));
    }


    public function edit(Payroll $payroll) {
        abort_if(Gate::denies('edit_payrolls'), 403);

        return view('hrm::payrolls.edit', compact('payroll'));
    }


    public function update(Request $request, Payroll $payroll) {
        abort_if(Gate::denies('edit_payrolls'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'base_pay' => 'required|numeric|max:2147483647',
            'overtime_pay' => 'nullable|numeric|max:2147483647',
            'bonus_pay' => 'nullable|numeric|max:2147483647',
            'deductions' => 'nullable|numeric|max:2147483647',
            'advance_deduction' => 'nullable|numeric|max:2147483647',
            'payment_method' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        $basePay = $request->base_pay ?? 0;
        $overtimePay = $request->overtime_pay ?? 0;
        $bonusPay = $request->bonus_pay ?? 0;
        $deductions = $request->deductions ?? 0;
        $advanceDeduction = $request->advance_deduction ?? 0;

        $totalPaid = $basePay + $overtimePay + $bonusPay - $deductions - $advanceDeduction;

        $payroll->update([
            'employee_id' => $request->employee_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_pay' => $basePay,
            'overtime_pay' => $overtimePay,
            'bonus_pay' => $bonusPay,
            'deductions' => $deductions,
            'advance_deduction' => $advanceDeduction,
            'total_paid' => max(0, $totalPaid),
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        toast('Payroll Updated!', 'info');

        return redirect()->route('payrolls.index');
    }


    public function destroy(Payroll $payroll) {
        abort_if(Gate::denies('delete_payrolls'), 403);

        $payroll->delete();

        toast('Payroll Deleted!', 'warning');

        return redirect()->route('payrolls.index');
    }

    public function getOvertime($employee, $start, $end) {
        abort_if(Gate::denies('create_payrolls'), 403);

        $total = Overtime::where('employee_id', $employee)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        return response()->json([
            'overtime_pay' => $total / 100,
        ]);
    }
}
