<?php

namespace Modules\Cart\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cart\Database\Factories\CartFactory;

class Cart extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'total_price',
        'discounted_price',
        'uuid',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

        // اضافه کردن متد برای مدیریت سبد‌های تو در تو
        public function subCarts()
        {
            return $this->hasMany(Cart::class, 'parent_cart_id');
        }
    


}
