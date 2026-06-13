<?php

namespace Modules\Manufacturing\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'completed_at',
        'conversion_cost',
        'cost_per_output_kg',
        'date',
        'input_weight',
        'note',
        'output_weight',
        'raw_material_cost',
        'reference',
        'status',
        'total_cost',
        'user_id',
        'wastage_weight',
    ];

    protected $casts = [
        'date' => 'date',
        'completed_at' => 'datetime',
        'input_weight' => 'decimal:3',
        'output_weight' => 'decimal:3',
        'wastage_weight' => 'decimal:3',
    ];

    public function inputs()
    {
        return $this->hasMany(ProductionBatchInput::class);
    }

    public function outputs()
    {
        return $this->hasMany(ProductionBatchOutput::class);
    }

    public function expenses()
    {
        return $this->hasMany(ProductionBatchExpense::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public static function nextBatchNumber(): int
    {
        return ((int) static::max('batch_number')) + 1;
    }

    public static function nextReference(): string
    {
        return make_reference_id('PB', static::nextBatchNumber());
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->batch_number) {
                $model->batch_number = static::nextBatchNumber();
            }

            if (!$model->reference) {
                $model->reference = make_reference_id('PB', $model->batch_number);
            }
        });
    }

    public function recalculateCosts(): void
    {
        $this->loadMissing(['inputs', 'outputs', 'expenses']);

        $rawMaterialCost = $this->inputs->sum('sub_total');
        $conversionCost = $this->expenses->sum('amount');
        $totalCost = $rawMaterialCost + $conversionCost;
        $outputWeight = (float) $this->output_weight;
        $costPerOutputKg = $outputWeight > 0 ? $totalCost / $outputWeight : 0;

        $this->update([
            'raw_material_cost' => $rawMaterialCost,
            'conversion_cost' => $conversionCost,
            'total_cost' => $totalCost,
            'cost_per_output_kg' => $costPerOutputKg,
        ]);

        $productCostAdjustments = [];

        foreach ($this->outputs as $output) {
            $newSubTotal = ((float) $output->quantity) * $costPerOutputKg;
            $difference = $newSubTotal - (float) $output->sub_total;

            if ($output->product_id && $difference != 0.0) {
                $productCostAdjustments[$output->product_id] = ($productCostAdjustments[$output->product_id] ?? 0) + $difference;
            }

            $output->update([
                'unit_cost' => $costPerOutputKg,
                'sub_total' => $newSubTotal,
            ]);
        }

        if (!empty($productCostAdjustments)) {
            $products = Product::whereIn('id', array_keys($productCostAdjustments))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($productCostAdjustments as $productId => $difference) {
                $product = $products->get($productId);

                if (!$product || (float) $product->product_quantity <= 0) {
                    continue;
                }

                $product->update([
                    'product_cost' => max(0, (float) $product->product_cost + ($difference / (float) $product->product_quantity)),
                ]);
            }
        }
    }

    public function getRawMaterialCostAttribute($value)
    {
        return $value / 100;
    }

    public function setRawMaterialCostAttribute($value)
    {
        $this->attributes['raw_material_cost'] = (int) round(((float) $value) * 100);
    }

    public function getConversionCostAttribute($value)
    {
        return $value / 100;
    }

    public function setConversionCostAttribute($value)
    {
        $this->attributes['conversion_cost'] = (int) round(((float) $value) * 100);
    }

    public function getTotalCostAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalCostAttribute($value)
    {
        $this->attributes['total_cost'] = (int) round(((float) $value) * 100);
    }

    public function getCostPerOutputKgAttribute($value)
    {
        return $value / 100;
    }

    public function setCostPerOutputKgAttribute($value)
    {
        $this->attributes['cost_per_output_kg'] = (int) round(((float) $value) * 100);
    }
}
