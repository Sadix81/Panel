<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Cart\Models\CartItem;
use Modules\Category\Models\Category;
use Modules\Color\Models\Color;
use Modules\Material\Models\Material;
use Modules\Product\Models\Product;
use Modules\Promotion\Models\Promotion;
use Modules\Size\Models\Size;
use Modules\Weight\Models\Weight;

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
        'material_id',
        'weight_id',
        'category_id',
        'type',
        'amount',
        'discounted_price',
        'previous_discount_type',
        'previous_discount_amount',
        'previous_discounted_price',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

        public function material()
    {
        return $this->belongsTo(Material::class);
    }

        public function weight()
    {
        return $this->belongsTo(Weight::class);
    }

    public function discountPromotions()
    {

        return $this->belongsTo(Promotion::class);
    }

    public function cartitem()
    {
        return $this->belongsTo(CartItem::class);
    }
}
