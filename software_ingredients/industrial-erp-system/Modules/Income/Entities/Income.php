<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'category_id',
        'date',
        'details',
        'reference',
    ];

    public function category() {
        return $this->belongsTo(IncomeCategory::class, 'category_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Income::max('id') + 1;
            $model->reference = make_reference_id('INC', $number);
        });
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function setAmountAttribute($value) {
        $this->attributes['amount'] = ($value * 100);
    }

    public function getAmountAttribute($value) {
        return ($value / 100);
    }
}
