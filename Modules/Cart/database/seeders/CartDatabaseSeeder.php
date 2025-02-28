<?php

namespace Modules\Cart\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cart\Models\Cart;

class CartDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [1, 2];

        foreach ($userIds as $userId) {
            Cart::create([
                'user_id' => $userId,
                'total_price' => 0, // مقدار پیش فرض
                'discounted_price' => 0, // مقدار پیش فرض
            ]);
        }
    }
}
