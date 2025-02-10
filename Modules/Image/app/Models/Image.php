<?php

namespace Modules\Image\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

// use Modules\Image\Database\Factories\ImageFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
        'image_type',
        'image_size',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
