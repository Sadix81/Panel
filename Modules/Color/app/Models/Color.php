<?php

namespace Modules\Color\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

// use Modules\Color\Database\Factories\ColorFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function property()
    {
        return $this->hasMany(Product::class);
    }
}
