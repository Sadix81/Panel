<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Models\Product;
use Modules\Property\Models\Property;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            $product = Product::create([
                'name' => 'Product '.$i,
                'description' => 'Description for product '.$i,
                'status' => '1',
                'thumbnail' => 'thumbnail_'.$i.'.jpg', // فرض کنید تصاویر در public/images وجود دارند
            ]);

            $typeOptions = ['fixed', 'percentage'];
            $type = $typeOptions[array_rand($typeOptions)];

            Property::create([
                'price' => rand(100, 9999),
                'quantity' => rand(1, 20),
                'product_id' => $product->id,
                'color_id' => rand(1, 5),
                'size_id' => rand(1, 5),
                'category_id' => rand(1, 5),
                'type' => $type,
                'amount' => rand(50, 100),
                'discounted_price' => rand(50, 300),
            ]);
        }
    }
}
