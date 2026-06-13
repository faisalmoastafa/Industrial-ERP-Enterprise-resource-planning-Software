<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class SaleDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost_price',
        'gross_profit',
        'price',
        'product_code',
        'product_discount_amount',
        'product_discount_type',
        'product_id',
        'product_name',
        'product_tax_amount',
        'quantity',
        'sale_id',
        'sub_total',
        'unit_price',
    ];

    protected $with = ['product'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function getPriceAttribute($value) {
        return $value / 100;
    }

    public function getUnitPriceAttribute($value) {
        return $value / 100;
    }

    public function getSubTotalAttribute($value) {
        return $value / 100;
    }

    public function getProductDiscountAmountAttribute($value) {
        return $value / 100;
    }

    public function getProductTaxAmountAttribute($value) {
        return $value / 100;
    }

    public function getCostPriceAttribute($value) {
        return $value / 100;
    }

    public function setCostPriceAttribute($value) {
        $this->attributes['cost_price'] = (int) round(((float) $value) * 100);
    }

    public function getGrossProfitAttribute($value) {
        return $value / 100;
    }

    public function setGrossProfitAttribute($value) {
        $this->attributes['gross_profit'] = (int) round(((float) $value) * 100);
    }
}
