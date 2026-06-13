<?php

namespace Modules\People\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{

    use HasFactory;

    protected $fillable = [
        'address',
        'city',
        'country',
        'customer_email',
        'customer_name',
        'customer_phone',
        'opening_balance',
    ];

    public function setOpeningBalanceAttribute($value) {
        $this->attributes['opening_balance'] = (int) round(((float) $value) * 100);
    }

    public function getOpeningBalanceAttribute($value) {
        return ((int) $value) / 100;
    }

    protected static function newFactory() {
        return \Modules\People\Database\factories\CustomerFactory::new();
    }

}
