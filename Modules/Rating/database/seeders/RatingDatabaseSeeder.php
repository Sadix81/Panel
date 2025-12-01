<?php

namespace Modules\Rating\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Rating\Models\Rate;

class RatingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 30; $i++) {
            Rate::create([
                'user_id' => rand(1, 2),
                'product_id' => rand(1, 6),
                'rating' => rand(1, 5),
            ]);
        }
    }
}
