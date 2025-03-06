<?php

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Comment\Models\Comment;
use Modules\Product\Models\Product;

class CommentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all()->pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            if ($i === 1) {
                $parentId = null; // برای کامنت اول
            } else {
                // انتخاب parent_id تصادفی که بزرگ‌تر از id کامنت جاری باشد
                $parentId = rand(1, $i - 1);
            }

            Comment::create([
                'text' => "comment-seeder-database-test . $i",
                'product_id' => $products[array_rand($products)],
                'parent_id' => $parentId,
                'user_id' => rand(1, 2),
                'status' => rand(0,1),
            ]);
        }
    }
}
