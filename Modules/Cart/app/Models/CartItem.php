<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Property\Models\Property;

// use Modules\Cart\Database\Factories\CartItemFactory;

class CartItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'cart_id',
        'product_id',
        'property_id',
        'quantity',
    ];

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Property::class);
    }

}
