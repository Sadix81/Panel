<?php

namespace Modules\Weight\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Property\Models\Property;

// use Modules\Weight\Database\Factories\WeightFactory;

class Weight extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'weight_value'
    ];

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
