<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'base_pay',
        'overtime_pay',
        'bonus_pay',
        'deductions',
        'advance_deduction',
        'total_paid',
        'payment_method',
        'note',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function setBasePayAttribute($value) {
        $this->attributes['base_pay'] = ($value * 100);
    }

    public function getBasePayAttribute($value) {
        return ($value / 100);
    }

    public function setOvertimePayAttribute($value) {
        $this->attributes['overtime_pay'] = ($value * 100);
    }

    public function getOvertimePayAttribute($value) {
        return ($value / 100);
    }

    public function setBonusPayAttribute($value) {
        $this->attributes['bonus_pay'] = ($value * 100);
    }

    public function getBonusPayAttribute($value) {
        return ($value / 100);
    }

    public function setDeductionsAttribute($value) {
        $this->attributes['deductions'] = ($value * 100);
    }

    public function getDeductionsAttribute($value) {
        return ($value / 100);
    }

    public function setAdvanceDeductionAttribute($value) {
        $this->attributes['advance_deduction'] = ($value * 100);
    }

    public function getAdvanceDeductionAttribute($value) {
        return ($value / 100);
    }

    public function setTotalPaidAttribute($value) {
        $this->attributes['total_paid'] = ($value * 100);
    }

    public function getTotalPaidAttribute($value) {
        return ($value / 100);
    }
}
