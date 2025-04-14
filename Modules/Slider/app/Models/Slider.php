<?php

namespace Modules\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Slider\Database\Factories\SliderFactory;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'slider_image_url',
        'slider_image_type',
        'slider_image_size',
    ];
}
