<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\DataTables\BonusesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\HRM\Entities\Bonus;

class BonusController extends Controller
{

    public function index(BonusesDataTable $dataTable) {
        abort_if(Gate::denies('access_bonuses'), 403);

        return $dataTable->render('hrm::bonuses.index');
    }


    public function create() {
        abort_if(Gate::denies('access_bonuses'), 403);

        return view('hrm::bonuses.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('access_bonuses'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'bonus_name' => 'required|string|max:255',
            'amount' => 'required|numeric|max:2147483647',
            'note' => 'nullable|string|max:1000',
        ]);

        Bonus::create([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'bonus_name' => $request->bonus_name,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        toast('Bonus Created!', 'success');

        return redirect()->route('bonuses.index');
    }


    public function edit(Bonus $bonus) {
        abort_if(Gate::denies('access_bonuses'), 403);

        return view('hrm::bonuses.edit', compact('bonus'));
    }


    public function update(Request $request, Bonus $bonus) {
        abort_if(Gate::denies('access_bonuses'), 403);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'bonus_name' => 'required|string|max:255',
            'amount' => 'required|numeric|max:2147483647',
            'note' => 'nullable|string|max:1000',
        ]);

        $bonus->update([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'bonus_name' => $request->bonus_name,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        toast('Bonus Updated!', 'info');

        return redirect()->route('bonuses.index');
    }


    public function destroy(Bonus $bonus) {
        abort_if(Gate::denies('access_bonuses'), 403);

        $bonus->delete();

        toast('Bonus Deleted!', 'warning');

        return redirect()->route('bonuses.index');
    }
}
