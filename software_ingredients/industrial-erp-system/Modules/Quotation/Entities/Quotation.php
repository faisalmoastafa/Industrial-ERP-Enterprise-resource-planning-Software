<?php

namespace Modules\Quotation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\People\Entities\Customer;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'date',
        'discount_amount',
        'discount_percentage',
        'note',
        'reference',
        'shipping_amount',
        'status',
        'tax_amount',
        'tax_percentage',
        'total_amount',
    ];

    public function quotationDetails() {
        return $this->hasMany(QuotationDetails::class, 'quotation_id', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Quotation::max('id') + 1;
            $model->reference = make_reference_id('QT', $number);
        });
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function getShippingAmountAttribute($value) {
        return $value / 100;
    }

    public function getPaidAmountAttribute($value) {
        return $value / 100;
    }

    public function getTotalAmountAttribute($value) {
        return $value / 100;
    }

    public function getDueAmountAttribute($value) {
        return $value / 100;
    }

    public function getTaxAmountAttribute($value) {
        return $value / 100;
    }

    public function getDiscountAmountAttribute($value) {
        return $value / 100;
    }
}
