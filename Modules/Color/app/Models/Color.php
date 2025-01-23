<?php

namespace Modules\Color\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Models\Product;

// use Modules\Color\Database\Factories\ColorFactory;

class Color extends Model
{
    use HasFactory;


    protected $fillable = [
        'name'
    ];

    public function property()
    {
        return $this->hasMany(Product::class);
    }
}
