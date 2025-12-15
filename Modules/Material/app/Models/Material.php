<?php

namespace Modules\Material\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Material\Database\Factories\MaterialFactory;

class Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title'
    ];

    // protected static function newFactory(): MaterialFactory
    // {
    //     // return MaterialFactory::new();
    // }
}
