<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Models\Category;
use Modules\Comment\Models\Comment;
use Modules\Favorite\Models\Favorite;
use Modules\Image\Models\Image;
use Modules\Property\Models\Property;

// use Modules\Product\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'thumbnail',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function favorite(){
        return $this->hasMany(Favorite::class);
    }
}
