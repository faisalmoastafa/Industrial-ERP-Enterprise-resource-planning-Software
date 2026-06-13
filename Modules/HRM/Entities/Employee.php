<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'designation',
        'salary_type',
        'base_salary',
        'overtime_rate',
        'address',
        'status',
    ];

    public function overtimes() {
        return $this->hasMany(Overtime::class);
    }

    public function bonuses() {
        return $this->hasMany(Bonus::class);
    }

    public function payrolls() {
        return $this->hasMany(Payroll::class);
    }

    public function setBaseSalaryAttribute($value) {
        $this->attributes['base_salary'] = ($value * 100);
    }

    public function getBaseSalaryAttribute($value) {
        return ($value / 100);
    }

    public function setOvertimeRateAttribute($value) {
        $this->attributes['overtime_rate'] = ($value * 100);
    }

    public function getOvertimeRateAttribute($value) {
        return ($value / 100);
    }
}
