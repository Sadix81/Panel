<?php

namespace Modules\Material\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Models\Property;

// use Modules\Material\Database\Factories\MaterialFactory;

class Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
    ];

    public function property()
    {
        return $this->hasMany(Property::class);
    }
}
