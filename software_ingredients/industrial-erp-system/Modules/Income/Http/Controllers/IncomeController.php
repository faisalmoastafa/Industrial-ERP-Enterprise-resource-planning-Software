<?php

namespace Modules\Income\Http\Controllers;

use Modules\Income\DataTables\IncomesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Income\Entities\Income;

class IncomeController extends Controller
{

    public function index(IncomesDataTable $dataTable) {
        abort_if(Gate::denies('access_incomes'), 403);

        return $dataTable->render('income::incomes.index');
    }


    public function create() {
        abort_if(Gate::denies('create_incomes'), 403);

        return view('income::incomes.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_incomes'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'category_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'details' => 'nullable|string|max:1000'
        ]);

        Income::create([
            'date' => $request->date,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'details' => $request->details
        ]);

        toast('Income Created!', 'success');

        return redirect()->route('incomes.index');
    }


    public function edit(Income $income) {
        abort_if(Gate::denies('edit_incomes'), 403);

        return view('income::incomes.edit', compact('income'));
    }


    public function show(Income $income) {
        abort_if(Gate::denies('access_incomes'), 403);

        $income->load('category');

        return view('income::incomes.show', compact('income'));
    }


    public function update(Request $request, Income $income) {
        abort_if(Gate::denies('edit_incomes'), 403);

        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|max:255',
            'category_id' => 'required',
            'amount' => 'required|numeric|max:2147483647',
            'details' => 'nullable|string|max:1000'
        ]);

        $income->update([
            'date' => $request->date,
            'reference' => $request->reference,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'details' => $request->details
        ]);

        toast('Income Updated!', 'info');

        return redirect()->route('incomes.index');
    }


    public function destroy(Income $income) {
        abort_if(Gate::denies('delete_incomes'), 403);

        $income->delete();

        toast('Income Deleted!', 'warning');

        return redirect()->route('incomes.index');
    }
}
