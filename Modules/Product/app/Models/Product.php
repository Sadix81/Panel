<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Models\Category;

// use Modules\Product\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'status',
        'category_id',
        // 'price',
        // 'Quantity',
        // 'color',
        // 'image_url',
    ];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }


}
