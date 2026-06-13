<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Manufacturing\Entities\ProductionBatch;
use Modules\Manufacturing\Entities\ProductionBatchExpense;

class ProductionBatchExpenseController extends Controller
{
    public function create(Request $request)
    {
        abort_if(Gate::denies('create_expenses'), 403);
        abort_if(Gate::denies('access_manufacturing'), 403);

        $batches = ProductionBatch::completed()
            ->latest('date')
            ->latest('id')
            ->get(['id', 'reference', 'batch_number', 'date', 'conversion_cost']);

        $nextReference = ProductionBatchExpense::nextReference();
        $selectedBatchId = $request->integer('batch_id') ?: null;

        return view('manufacturing::conversion-expenses.create', compact('batches', 'nextReference', 'selectedBatchId'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create_expenses'), 403);
        abort_if(Gate::denies('access_manufacturing'), 403);

        $validated = $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:2147483647',
            'note' => 'nullable|string|max:1000',
        ]);

        $batch = DB::transaction(function () use ($validated) {
            $batch = ProductionBatch::whereKey($validated['production_batch_id'])
                ->lockForUpdate()
                ->firstOrFail();

            ProductionBatchExpense::create([
                'production_batch_id' => $batch->id,
                'date' => $validated['date'],
                'name' => $validated['name'],
                'amount' => $validated['amount'],
                'note' => $validated['note'] ?? null,
                'user_id' => auth()->id(),
            ]);

            $batch->refresh();
            $batch->load(['inputs', 'outputs', 'expenses']);
            $batch->recalculateCosts();

            return $batch;
        });

        toast('Conversion Expense Added!', 'success');

        return redirect()->route('production-batches.show', $batch);
    }
}
