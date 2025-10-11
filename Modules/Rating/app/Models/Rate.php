<?php

namespace Modules\Rating\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

// use Modules\Rating\Database\Factories\RateFactory;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'product_id',
        'user_id',
        'totalrating',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
