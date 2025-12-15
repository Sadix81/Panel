<?php

namespace Modules\Weight\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Weight\Models\Weight;

class WeightDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weight = [
            ['title' => 'g', 'weight_value' => 0.001], // 1 گرم = 0.001 کیلوگرم
            ['title' => 'kg', 'weight_value' => 1], // 1 کیلوگرم
            ['title' => 'ton', 'weight_value' => 1000], // 1 تن = 1000 کیلوگرم
            ['title' => 'mg', 'weight_value' => 0.000001], // 1 میلی‌گرم = 0.000001 کیلوگرم
            ['title' => 'lb', 'weight_value' => 0.453592], // 1 پوند = 0.453592 کیلوگرم
            ['title' => 'oz', 'weight_value' => 0.0283495], // 1 اونس = 0.0283495 کیلوگرم
        ];

        Weight::insert($weight);
    }
}
