<?php

namespace Modules\Discount\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

// use Modules\Discount\Database\Factories\DiscountFactory;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'amount',
        'minimum_purchase',
        'start_date',
        'end_date',
        'conditions',
        'usage_limit',
        'used_count',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
