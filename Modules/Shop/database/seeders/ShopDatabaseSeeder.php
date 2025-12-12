<?php

namespace Modules\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Shop\Models\Shop;

class ShopDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shop::create([
            'name' => 'GhalebYaran',
            'telephone' => '123456789',
            'email' => 'GhalebYaran@gmail.com',
            'country' => 'Iran',
            'province' => 'Golestan',
            'city' => 'Gorgan',
            'address' => 'AAAAAAAAAAA',
            'codepost' => '1234#232323',
        ]);
    }
}
