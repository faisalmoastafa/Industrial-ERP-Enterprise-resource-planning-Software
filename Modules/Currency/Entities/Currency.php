<?php

namespace Modules\Currency\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'currency_name',
        'decimal_separator',
        'exchange_rate',
        'symbol',
        'thousand_separator',
    ];

}
