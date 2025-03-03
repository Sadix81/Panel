<?php

namespace Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Property\Models\Property;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'type',
        'amount',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class);
    }
}
