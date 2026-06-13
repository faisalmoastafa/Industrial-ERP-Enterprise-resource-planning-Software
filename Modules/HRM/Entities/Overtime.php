<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'hours',
        'rate_per_hour',
        'amount',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function setAmountAttribute($value) {
        $this->attributes['amount'] = ($value * 100);
    }

    public function getAmountAttribute($value) {
        return ($value / 100);
    }

    public function setRatePerHourAttribute($value) {
        $this->attributes['rate_per_hour'] = ($value * 100);
    }

    public function getRatePerHourAttribute($value) {
        return ($value / 100);
    }
}
