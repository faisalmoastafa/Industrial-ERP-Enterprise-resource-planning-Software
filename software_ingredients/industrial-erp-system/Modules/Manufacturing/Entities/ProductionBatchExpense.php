<?php

namespace Modules\Manufacturing\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
        'name',
        'note',
        'production_batch_id',
        'reference',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public static function nextReference(): string
    {
        return make_reference_id('CE', ((int) static::max('id')) + 1);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->reference) {
                $model->reference = static::nextReference();
            }
        });
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int) round(((float) $value) * 100);
    }
}
