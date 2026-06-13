<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'category_description',
    ];

    public function incomes() {
        return $this->hasMany(Income::class, 'category_id', 'id');
    }
}
