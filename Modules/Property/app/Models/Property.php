<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Models\Category;
use Modules\Color\Models\Color;
use Modules\Product\Models\Product;
use Modules\Size\Models\Size;

// use Modules\Property\Database\Factories\PropertyFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'quantity',
        'product_id',
        'color_id',
        'size_id',
        'category_id',
    ];



    public function products()
{
    return $this->belongsToMany(Product::class);
}

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }


}
