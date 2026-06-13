<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;

class PartyPayment extends Model
{
    protected $fillable = [
        'amount',
        'date',
        'note',
        'party_id',
        'party_name',
        'party_type',
        'payment_method',
        'payment_type',
        'reference',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $number = static::max('id') + 1;
            $model->reference = $model->reference ?: make_reference_id('PY', $number);
        });
    }

    public function setAmountAttribute($value): void
    {
        $this->attributes['amount'] = (int) round(((float) $value) * 100);
    }

    public function getAmountAttribute($value): float
    {
        return ((int) $value) / 100;
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->format('d M, Y');
    }

    public function party()
    {
        return $this->party_type === 'customer'
            ? Customer::find($this->party_id)
            : Supplier::find($this->party_id);
    }

    public function signedAmount(): float
    {
        if ($this->party_type === 'customer') {
            return $this->payment_type === 'pay_later' ? $this->amount : -$this->amount;
        }

        return $this->payment_type === 'pay_later' ? -$this->amount : $this->amount;
    }
}
