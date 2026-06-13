<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Notifications\NotifyQuantityAlert;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{

    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'product_type',
        'product_barcode_symbology',
        'product_code',
        'product_cost',
        'product_name',
        'product_note',
        'product_order_tax',
        'product_price',
        'product_quantity',
        'product_stock_alert',
        'product_tax_type',
        'product_unit',
    ];

    protected $with = ['media'];

    public const TYPE_FINISHED = 'finished';
    public const TYPE_RAW_MATERIAL = 'raw_material';

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function scopeFinished($query) {
        return $query->where('product_type', self::TYPE_FINISHED);
    }

    public function scopeRawMaterial($query) {
        return $query->where('product_type', self::TYPE_RAW_MATERIAL);
    }

    public function isRawMaterial(): bool {
        return $this->product_type === self::TYPE_RAW_MATERIAL;
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/fallback_product_image.png');
    }

    public function registerMediaConversions(Media $media = null): void {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function setProductCostAttribute($value) {
        $this->attributes['product_cost'] = ($value * 100);
    }

    public function getProductCostAttribute($value) {
        return ($value / 100);
    }

    public function setProductPriceAttribute($value) {
        $this->attributes['product_price'] = ($value * 100);
    }

    public function getProductPriceAttribute($value) {
        return ($value / 100);
    }
}
