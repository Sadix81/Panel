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
            ['name' => 'green', 'code' => '#00FF00'],
            ['name' => 'blue', 'code' => '#0000FF'],
            ['name' => 'yellow', 'code' => '#FFFF00'],
            ['name' => 'black', 'code' => '#000000'],
            ['name' => 'white', 'code' => '#FFFFFF'],
            ['name' => 'brown', 'code' => '#A52A2A'],
        ];

        Color::insert($colors);
    }
}
