<?php

namespace Modules\Favorite\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Favorite\Models\Favorite;

class FavoriteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 6; $i++) {
            Favorite::create([
                'user_id' => rand(1, 2),
                'product_id' => rand(1, 6),
            ]);
        }
    }
}
