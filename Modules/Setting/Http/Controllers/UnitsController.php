<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Setting\Entities\Unit;

class   UnitsController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_units'), 403);

        $units = Unit::all();

        return view('setting::units.index', [
            'units' => $units
        ]);
    }

    public function create() {
        abort_if(Gate::denies('create_units'), 403);

        return view('setting::units.create');
    }

    public function store(Request $request) {
        abort_if(Gate::denies('create_units'), 403);

        $request->validate([
            'name'       => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'operator'        => 'nullable|string|max:255',
            'operation_value' => 'nullable|numeric|min:0'
        ]);

        Unit::create([
            'name'            => $request->name,
            'short_name'      => $request->short_name,
            'operator'        => $request->operator,
            'operation_value' => $request->operation_value,
        ]);

        toast('Unit Created!', 'success');

        return redirect()->route('units.index');
    }

    public function edit(Unit $unit) {
        abort_if(Gate::denies('edit_units'), 403);

        return view('setting::units.edit', [
            'unit' => $unit
        ]);
    }

    public function update(Request $request, Unit $unit) {
        abort_if(Gate::denies('edit_units'), 403);

        $request->validate([
            'name'       => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'operator'        => 'nullable|string|max:255',
            'operation_value' => 'nullable|numeric|min:0'
        ]);

        $unit->update([
            'name'            => $request->name,
            'short_name'      => $request->short_name,
            'operator'        => $request->operator,
            'operation_value' => $request->operation_value,
        ]);

        toast('Unit Updated!', 'info');

        return redirect()->route('units.index');
    }

    public function destroy(Unit $unit) {
        abort_if(Gate::denies('delete_units'), 403);

        $unit->delete();

        toast('Unit Deleted!', 'warning');

        return redirect()->route('units.index');
    }
}
