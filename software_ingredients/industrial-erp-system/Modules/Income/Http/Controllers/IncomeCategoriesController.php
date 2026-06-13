<?php

namespace Modules\Income\Http\Controllers;

use Modules\Income\DataTables\IncomeCategoriesDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Income\Entities\IncomeCategory;

class IncomeCategoriesController extends Controller
{

    public function index(IncomeCategoriesDataTable $dataTable) {
        abort_if(Gate::denies('access_income_categories'), 403);

        return $dataTable->render('income::categories.index');
    }

    public function store(Request $request) {
        abort_if(Gate::denies('access_income_categories'), 403);

        $request->validate([
            'category_name' => 'required|string|max:255|unique:income_categories,category_name',
            'category_description' => 'nullable|string|max:1000'
        ]);

        IncomeCategory::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description
        ]);

        toast('Income Category Created!', 'success');

        return redirect()->route('income-categories.index');
    }


    public function edit(IncomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        return view('income::categories.edit', compact('incomeCategory'));
    }


    public function update(Request $request, IncomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        $request->validate([
            'category_name' => 'required|string|max:255|unique:income_categories,category_name,' . $incomeCategory->id,
            'category_description' => 'nullable|string|max:1000'
        ]);

        $incomeCategory->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description
        ]);

        toast('Income Category Updated!', 'info');

        return redirect()->route('income-categories.index');
    }


    public function destroy(IncomeCategory $incomeCategory) {
        abort_if(Gate::denies('access_income_categories'), 403);

        if ($incomeCategory->incomes()->isNotEmpty()) {
            return back()->withErrors('Can\'t delete because there are incomes associated with this category.');
        }

        $incomeCategory->delete();

        toast('Income Category Deleted!', 'warning');

        return redirect()->route('income-categories.index');
    }
}
