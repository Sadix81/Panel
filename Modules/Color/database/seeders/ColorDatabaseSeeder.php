<?php

namespace Modules\Color\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Color\Models\Color;

class ColorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'red'],
            ['name' => 'blue'],
            ['name' => 'yellow'],
            ['name' => 'black'],
            ['name' => 'white'],
            ['name' => 'broun'],
        ];

        Color::insert($colors);
    }
}
