<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Shop\Database\Factories\ShopFactory;

class Shop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'telephone',
        'email',
        'country',
        'province', // استان
        'city',
        'address',
        'codepost',

    ];

    // protected static function newFactory(): ShopFactory
    // {
    //     // return ShopFactory::new();
    // }
}
