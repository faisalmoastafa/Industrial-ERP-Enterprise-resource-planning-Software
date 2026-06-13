<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Manufacturing\Entities\ProductionBatch;
use Modules\Manufacturing\Entities\ProductionBatchExpense;
use Modules\Manufacturing\Entities\ProductionBatchInput;
use Modules\Manufacturing\Entities\ProductionBatchOutput;
use Modules\Product\Entities\Product;

class ProductionBatchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_manufacturing'), 403);

        $batches = ProductionBatch::withCount(['inputs', 'outputs'])
            ->latest('date')
            ->latest('id')
            ->get();

        return view('manufacturing::production-batches.index', compact('batches'));
    }

    public function report(Request $request)
    {
        abort_if(Gate::denies('access_manufacturing'), 403);

        $filters = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $baseQuery = ProductionBatch::completed()
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('date', '<=', $date));

        $summary = [
            'completed_batches' => (clone $baseQuery)->count(),
            'input_weight' => (clone $baseQuery)->sum('input_weight'),
            'output_weight' => (clone $baseQuery)->sum('output_weight'),
            'wastage_weight' => (clone $baseQuery)->sum('wastage_weight'),
            'raw_material_cost' => (clone $baseQuery)->sum('raw_material_cost') / 100,
            'conversion_cost' => (clone $baseQuery)->sum('conversion_cost') / 100,
            'total_cost' => (clone $baseQuery)->sum('total_cost') / 100,
        ];

        $summary['yield_percent'] = $summary['input_weight'] > 0
            ? ($summary['output_weight'] / $summary['input_weight']) * 100
            : 0;

        $batches = $baseQuery
            ->with(['inputs', 'outputs', 'expenses'])
            ->latest('date')
            ->latest('id')
            ->get();

        return view('manufacturing::production-reports.index', compact('batches', 'summary', 'filters'));
    }

    public function create()
    {
        abort_if(Gate::denies('create_production_batches'), 403);

        $rawMaterials = Product::rawMaterial()
            ->orderBy('product_name')
            ->get(['id', 'product_name', 'product_code', 'product_quantity', 'product_cost', 'product_unit']);

        $finishedProducts = Product::finished()
            ->orderBy('product_name')
            ->get(['id', 'product_name', 'product_code', 'product_quantity', 'product_cost', 'product_unit']);

        $nextBatchNumber = ProductionBatch::nextBatchNumber();
        $nextReference = make_reference_id('PB', $nextBatchNumber);

        return view('manufacturing::production-batches.create', compact(
            'finishedProducts',
            'nextBatchNumber',
            'nextReference',
            'rawMaterials'
        ));
    }

    public function edit(ProductionBatch $productionBatch)
    {
        abort_if(Gate::denies('access_manufacturing'), 403);

        $productionBatch->load(['inputs', 'outputs']);

        $rawMaterials = Product::rawMaterial()
            ->orderBy('product_name')
            ->get(['id', 'product_name', 'product_code', 'product_quantity', 'product_cost', 'product_unit']);

        $finishedProducts = Product::finished()
            ->orderBy('product_name')
            ->get(['id', 'product_name', 'product_code', 'product_quantity', 'product_cost', 'product_unit']);

        $nextBatchNumber = $productionBatch->batch_number;
        $nextReference = $productionBatch->reference;
        $batch = $productionBatch;

        return view('manufacturing::production-batches.create', compact(
            'batch',
            'finishedProducts',
            'nextBatchNumber',
            'nextReference',
            'rawMaterials'
        ));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('create_production_batches'), 403);

        $request->merge([
            'inputs' => $this->filledRows($request->input('inputs', []), ['product_id', 'quantity']),
            'outputs' => $this->filledRows($request->input('outputs', []), ['product_id', 'quantity']),
        ]);

        $validated = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:2000',
            'inputs' => 'required|array|min:1',
            'inputs.*.product_id' => 'required|integer|exists:products,id',
            'inputs.*.quantity' => 'required|numeric|gt:0',
            'inputs.*.unit_cost' => 'nullable|numeric|min:0',
            'outputs' => 'required|array|min:1',
            'outputs.*.product_id' => 'required|integer|exists:products,id',
            'outputs.*.quantity' => 'required|numeric|gt:0',
            'outputs.*.wire_size' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated) {
            $productIds = collect($validated['inputs'])
                ->pluck('product_id')
                ->merge(collect($validated['outputs'])->pluck('product_id'))
                ->unique()
                ->values();

            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $inputWeight = 0;
            $rawMaterialCost = 0;
            $outputWeight = 0;
            $conversionCost = 0;

            foreach ($validated['inputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];

                if (!$product->isRawMaterial()) {
                    throw ValidationException::withMessages([
                        'inputs' => "{$product->product_name} is not marked as a raw material product.",
                    ]);
                }

                if ((float) $product->product_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'inputs' => "Not enough stock for {$product->product_name}. Available: {$product->product_quantity}",
                    ]);
                }

                $unitCost = array_key_exists('unit_cost', $row) && $row['unit_cost'] !== null && $row['unit_cost'] !== ''
                    ? (float) $row['unit_cost']
                    : (float) $product->product_cost;

                $inputWeight += $quantity;
                $rawMaterialCost += $quantity * $unitCost;
            }

            foreach ($validated['outputs'] as $row) {
                $product = $products[$row['product_id']];

                if ($product->isRawMaterial()) {
                    throw ValidationException::withMessages([
                        'outputs' => "{$product->product_name} is marked as raw material, so it cannot be used as finished output.",
                    ]);
                }

                $outputWeight += (float) $row['quantity'];
            }

            if ($outputWeight <= 0) {
                throw ValidationException::withMessages([
                    'outputs' => 'Finished output weight must be greater than zero.',
                ]);
            }

            if ($outputWeight > $inputWeight) {
                throw ValidationException::withMessages([
                    'outputs' => 'Finished output cannot be greater than raw material input for this batch.',
                ]);
            }

            $totalCost = $rawMaterialCost + $conversionCost;
            $costPerOutputKg = $totalCost / $outputWeight;

            $batch = ProductionBatch::create([
                'date' => $validated['date'],
                'status' => 'Completed',
                'input_weight' => $inputWeight,
                'output_weight' => $outputWeight,
                'wastage_weight' => $inputWeight - $outputWeight,
                'raw_material_cost' => $rawMaterialCost,
                'conversion_cost' => $conversionCost,
                'total_cost' => $totalCost,
                'cost_per_output_kg' => $costPerOutputKg,
                'note' => $validated['note'] ?? null,
                'user_id' => auth()->id(),
                'completed_at' => now(),
            ]);

            foreach ($validated['inputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];
                $unitCost = array_key_exists('unit_cost', $row) && $row['unit_cost'] !== null && $row['unit_cost'] !== ''
                    ? (float) $row['unit_cost']
                    : (float) $product->product_cost;

                ProductionBatchInput::create([
                    'production_batch_id' => $batch->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'sub_total' => $quantity * $unitCost,
                ]);

                $product->update([
                    'product_quantity' => (float) $product->product_quantity - $quantity,
                ]);
            }

            foreach ($validated['outputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];
                $currentQuantity = (float) $product->product_quantity;
                $currentCost = (float) $product->product_cost;
                $newQuantity = $currentQuantity + $quantity;
                $newAverageCost = $newQuantity > 0
                    ? (($currentQuantity * $currentCost) + ($quantity * $costPerOutputKg)) / $newQuantity
                    : $costPerOutputKg;

                ProductionBatchOutput::create([
                    'production_batch_id' => $batch->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'wire_size' => $row['wire_size'] ?? null,
                    'quantity' => $quantity,
                    'unit_cost' => $costPerOutputKg,
                    'sub_total' => $quantity * $costPerOutputKg,
                ]);

                $product->update([
                    'product_quantity' => $newQuantity,
                    'product_cost' => $newAverageCost,
                ]);
            }

            Log::channel('security')->info('Production batch completed', [
                'user_id' => auth()->id(),
                'production_batch_id' => $batch->id,
                'reference' => $batch->reference,
                'input_weight' => $inputWeight,
                'output_weight' => $outputWeight,
                'wastage_weight' => $inputWeight - $outputWeight,
                'total_cost' => $totalCost,
            ]);
        });

        toast('Production Batch Completed!', 'success');

        return redirect()->route('production-batches.index');
    }

    public function update(Request $request, ProductionBatch $productionBatch)
    {
        abort_if(Gate::denies('access_manufacturing'), 403);

        $request->merge([
            'inputs' => $this->filledRows($request->input('inputs', []), ['product_id', 'quantity']),
            'outputs' => $this->filledRows($request->input('outputs', []), ['product_id', 'quantity']),
        ]);

        $validated = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:2000',
            'inputs' => 'required|array|min:1',
            'inputs.*.product_id' => 'required|integer|exists:products,id',
            'inputs.*.quantity' => 'required|numeric|gt:0',
            'inputs.*.unit_cost' => 'nullable|numeric|min:0',
            'outputs' => 'required|array|min:1',
            'outputs.*.product_id' => 'required|integer|exists:products,id',
            'outputs.*.quantity' => 'required|numeric|gt:0',
            'outputs.*.wire_size' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($productionBatch, $validated) {
            $productionBatch->load(['inputs', 'outputs', 'expenses']);

            $productIds = $productionBatch->inputs->pluck('product_id')
                ->merge($productionBatch->outputs->pluck('product_id'))
                ->merge(collect($validated['inputs'])->pluck('product_id'))
                ->merge(collect($validated['outputs'])->pluck('product_id'))
                ->filter()
                ->unique()
                ->values();

            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($productionBatch->outputs as $output) {
                if (!$output->product_id || !isset($products[$output->product_id])) {
                    continue;
                }

                $product = $products[$output->product_id];
                if ((float) $product->product_quantity < (float) $output->quantity) {
                    throw ValidationException::withMessages([
                        'outputs' => "Cannot update {$productionBatch->reference}. Some finished stock from this batch has already been used.",
                    ]);
                }
            }

            foreach ($productionBatch->outputs as $output) {
                if ($output->product_id && isset($products[$output->product_id])) {
                    $product = $products[$output->product_id];
                    $product->update([
                        'product_quantity' => (float) $product->product_quantity - (float) $output->quantity,
                    ]);
                }
            }

            foreach ($productionBatch->inputs as $input) {
                if ($input->product_id && isset($products[$input->product_id])) {
                    $product = $products[$input->product_id];
                    $product->update([
                        'product_quantity' => (float) $product->product_quantity + (float) $input->quantity,
                    ]);
                }
            }

            $inputWeight = 0;
            $rawMaterialCost = 0;
            $outputWeight = 0;
            $conversionCost = $productionBatch->expenses->sum('amount');

            foreach ($validated['inputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];

                if (!$product->isRawMaterial()) {
                    throw ValidationException::withMessages([
                        'inputs' => "{$product->product_name} is not marked as a raw material product.",
                    ]);
                }

                if ((float) $product->product_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'inputs' => "Not enough stock for {$product->product_name}. Available: {$product->product_quantity}",
                    ]);
                }

                $unitCost = array_key_exists('unit_cost', $row) && $row['unit_cost'] !== null && $row['unit_cost'] !== ''
                    ? (float) $row['unit_cost']
                    : (float) $product->product_cost;

                $inputWeight += $quantity;
                $rawMaterialCost += $quantity * $unitCost;
            }

            foreach ($validated['outputs'] as $row) {
                $product = $products[$row['product_id']];

                if ($product->isRawMaterial()) {
                    throw ValidationException::withMessages([
                        'outputs' => "{$product->product_name} is marked as raw material, so it cannot be used as finished output.",
                    ]);
                }

                $outputWeight += (float) $row['quantity'];
            }

            if ($outputWeight <= 0) {
                throw ValidationException::withMessages([
                    'outputs' => 'Finished output weight must be greater than zero.',
                ]);
            }

            if ($outputWeight > $inputWeight) {
                throw ValidationException::withMessages([
                    'outputs' => 'Finished output cannot be greater than raw material input for this batch.',
                ]);
            }

            $totalCost = $rawMaterialCost + $conversionCost;
            $costPerOutputKg = $totalCost / $outputWeight;

            $productionBatch->inputs()->delete();
            $productionBatch->outputs()->delete();

            $productionBatch->update([
                'date' => $validated['date'],
                'status' => 'Completed',
                'input_weight' => $inputWeight,
                'output_weight' => $outputWeight,
                'wastage_weight' => $inputWeight - $outputWeight,
                'raw_material_cost' => $rawMaterialCost,
                'conversion_cost' => $conversionCost,
                'total_cost' => $totalCost,
                'cost_per_output_kg' => $costPerOutputKg,
                'note' => $validated['note'] ?? null,
                'completed_at' => now(),
            ]);

            foreach ($validated['inputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];
                $unitCost = array_key_exists('unit_cost', $row) && $row['unit_cost'] !== null && $row['unit_cost'] !== ''
                    ? (float) $row['unit_cost']
                    : (float) $product->product_cost;

                ProductionBatchInput::create([
                    'production_batch_id' => $productionBatch->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'sub_total' => $quantity * $unitCost,
                ]);

                $product->update([
                    'product_quantity' => (float) $product->product_quantity - $quantity,
                ]);
            }

            foreach ($validated['outputs'] as $row) {
                $product = $products[$row['product_id']];
                $quantity = (float) $row['quantity'];
                $currentQuantity = (float) $product->product_quantity;
                $currentCost = (float) $product->product_cost;
                $newQuantity = $currentQuantity + $quantity;
                $newAverageCost = $newQuantity > 0
                    ? (($currentQuantity * $currentCost) + ($quantity * $costPerOutputKg)) / $newQuantity
                    : $costPerOutputKg;

                ProductionBatchOutput::create([
                    'production_batch_id' => $productionBatch->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'wire_size' => $row['wire_size'] ?? null,
                    'quantity' => $quantity,
                    'unit_cost' => $costPerOutputKg,
                    'sub_total' => $quantity * $costPerOutputKg,
                ]);

                $product->update([
                    'product_quantity' => $newQuantity,
                    'product_cost' => $newAverageCost,
                ]);
            }

            Log::channel('security')->info('Production batch updated', [
                'user_id' => auth()->id(),
                'production_batch_id' => $productionBatch->id,
                'reference' => $productionBatch->reference,
            ]);
        });

        toast('Production Batch Updated!', 'success');

        return redirect()->route('production-batches.index');
    }

    public function show(ProductionBatch $productionBatch)
    {
        abort_if(Gate::denies('show_production_batches'), 403);

        $productionBatch->load(['inputs.product', 'outputs.product', 'expenses']);

        return view('manufacturing::production-batches.show', [
            'batch' => $productionBatch,
        ]);
    }

    public function destroy(ProductionBatch $productionBatch)
    {
        abort_if(Gate::denies('delete_production_batches'), 403);

        DB::transaction(function () use ($productionBatch) {
            $productionBatch->load(['inputs', 'outputs']);

            $productIds = $productionBatch->inputs->pluck('product_id')
                ->merge($productionBatch->outputs->pluck('product_id'))
                ->filter()
                ->unique()
                ->values();

            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($productionBatch->outputs as $output) {
                if (!$output->product_id || !isset($products[$output->product_id])) {
                    continue;
                }

                $product = $products[$output->product_id];
                if ((float) $product->product_quantity < (float) $output->quantity) {
                    throw ValidationException::withMessages([
                        'batch' => "Cannot delete {$productionBatch->reference}. Some finished stock has already been sold or adjusted.",
                    ]);
                }
            }

            foreach ($productionBatch->outputs as $output) {
                if ($output->product_id && isset($products[$output->product_id])) {
                    $product = $products[$output->product_id];
                    $product->update([
                        'product_quantity' => (float) $product->product_quantity - (float) $output->quantity,
                    ]);
                }
            }

            foreach ($productionBatch->inputs as $input) {
                if ($input->product_id && isset($products[$input->product_id])) {
                    $product = $products[$input->product_id];
                    $product->update([
                        'product_quantity' => (float) $product->product_quantity + (float) $input->quantity,
                    ]);
                }
            }

            Log::channel('security')->warning('Production batch deleted and stock reversed', [
                'user_id' => auth()->id(),
                'production_batch_id' => $productionBatch->id,
                'reference' => $productionBatch->reference,
            ]);

            $productionBatch->delete();
        });

        toast('Production Batch Deleted and Stock Reversed!', 'warning');

        return redirect()->route('production-batches.index');
    }

    private function filledRows(array $rows, array $keys): array
    {
        return collect($rows)
            ->filter(function ($row) use ($keys) {
                foreach ($keys as $key) {
                    if (isset($row[$key]) && $row[$key] !== '') {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }
}
