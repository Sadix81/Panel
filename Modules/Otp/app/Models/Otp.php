<?php

namespace Modules\Otp\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Otp\Database\Factories\OtpFactory;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'otp',
        'expire_time',
        'user_id',
    ];

    public function users()
    {
        $this->belongsTo(User::class);
    }
}
