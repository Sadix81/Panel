<?php

namespace Modules\Size\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Models\Property;

// use Modules\Size\Database\Factories\SizeFactory;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
