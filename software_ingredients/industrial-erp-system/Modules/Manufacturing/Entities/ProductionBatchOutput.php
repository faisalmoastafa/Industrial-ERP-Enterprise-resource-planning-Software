<?php

namespace Modules\Manufacturing\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class ProductionBatchOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_id',
        'product_name',
        'production_batch_id',
        'quantity',
        'sub_total',
        'unit_cost',
        'wire_size',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUnitCostAttribute($value)
    {
        return $value / 100;
    }

    public function setUnitCostAttribute($value)
    {
        $this->attributes['unit_cost'] = (int) round(((float) $value) * 100);
    }

    public function getSubTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setSubTotalAttribute($value)
    {
        $this->attributes['sub_total'] = (int) round(((float) $value) * 100);
    }
}
