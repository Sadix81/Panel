<?php

namespace Modules\Image\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Image\Database\Factories\ImageFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
        'product_id',
    ];
}
