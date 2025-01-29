<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Models\Category;
use Modules\Comment\Models\Comment;
use Modules\Property\Models\Property;

// use Modules\Product\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'status',
        'color',
        // 'image_url',
    ];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

}
