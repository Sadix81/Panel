<?php

namespace Modules\Cart\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cart\Models\Cart;
use Modules\Property\Models\Property;

class CartDatabaseSeeder extends Seeder
{
    public function run(): void
    {        
        $userIds = [1, 2];

        $properties = Property::take(6)->get();

        foreach ($userIds as $userId) {
            $cart = Cart::create([
                'user_id' => $userId,
                'total_price' => 0,
                'discounted_price' => 0,
                'final_price' => 0,
            ]);

            $totalPrice = 0;
            $discounted_price = 0;

            foreach ($properties as $property) {
                $quantity = $property->quantity;

                $cart->cartItems()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $property->product_id,
                    'property_id' => $property->id,
                    'quantity' => $quantity,
                ]);

                $totalPrice += $property->price * $quantity;
                $discounted_price += $property->discounted_price * $quantity;
            }

            $final_price = $totalPrice - $discounted_price;

            $cart->update([
                'total_price' => $totalPrice,
                'discounted_price' => $discounted_price,
                'final_price' => $final_price,
            ]);
        }
    }
}