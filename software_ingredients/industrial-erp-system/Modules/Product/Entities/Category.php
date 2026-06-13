<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_code',
        'category_name',
    ];

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
