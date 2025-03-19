<?php

namespace Modules\Rating\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Rating\Database\Factories\RateFactory;

class Rate extends Model
{
    use HasFactory;


    protected $fillable = [
        'rating',
        'product_id',
        'user_id',
    ];
}
