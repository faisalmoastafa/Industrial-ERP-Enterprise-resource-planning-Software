<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\DataTables\OvertimesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\HRM\Entities\Overtime;
use Modules\HRM\Entities\Employee;

class OvertimeController extends Controller
{

    public function index(OvertimesDataTable $dataTable) {
        abort_if(Gate::denies('access_overtimes'), 403);

        return $dataTable->render('hrm::overtimes.index');
    }


    public function create() {
        abort_if(Gate::denies('access_overtimes'), 403);

        return view('hrm::overtimes.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_overtimes'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0|max:24',
            'rate_per_hour' => 'required|numeric|min:0|max:2147483647',
        ]);

        $amount = $request->hours * $request->rate_per_hour;

        Overtime::create([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'hours' => $request->hours,
            'rate_per_hour' => $request->rate_per_hour,
            'amount' => $amount,
        ]);

        toast('Overtime Created!', 'success');

        return redirect()->route('overtimes.index');
    }


    public function edit(Overtime $overtime) {
        abort_if(Gate::denies('access_overtimes'), 403);

        return view('hrm::overtimes.edit', compact('overtime'));
    }


    public function update(Request $request, Overtime $overtime) {
        abort_if(Gate::denies('access_overtimes'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0|max:24',
            'rate_per_hour' => 'required|numeric|min:0|max:2147483647',
        ]);

        $amount = $request->hours * $request->rate_per_hour;

        $overtime->update([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'hours' => $request->hours,
            'rate_per_hour' => $request->rate_per_hour,
            'amount' => $amount,
        ]);

        toast('Overtime Updated!', 'info');

        return redirect()->route('overtimes.index');
    }


    public function destroy(Overtime $overtime) {
        abort_if(Gate::denies('access_overtimes'), 403);

        $overtime->delete();

        toast('Overtime Deleted!', 'warning');

        return redirect()->route('overtimes.index');
    }
}
