<?php

namespace Modules\HRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'note',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
