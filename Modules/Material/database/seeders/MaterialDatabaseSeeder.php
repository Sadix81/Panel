<?php

namespace Modules\Material\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Material\Models\Material;

class MaterialDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $material = [
            ['title' => 'طلا'],
            ['title' => 'نقره'],
            ['title' => 'برنز'],
            ['title' => 'جیوه'],
            ['title' => 'آهن'],
            ['title' => 'پارچه'],
            ['title' => 'ابریشم'],
        ];

        Material::insert($material);
    }
}
