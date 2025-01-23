<?php

namespace Modules\Size\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Size\Models\Size;

class SizeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['title' => 'S'],
            ['title' => 'M'], 
            ['title' => 'L'], 
            ['title' => 'XL'], 
            ['title' => 'XXL'], 
            ['title' => 'XXXL'], 
        ];
        
        Size::insert($sizes);
    }
}
