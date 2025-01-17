<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Models\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentCategories = [
            ['name' => 'list-1'],
            ['name' => 'list-2'], 
        ];
        
        $insertedParentCategories = Category::insert($parentCategories);
        
        $categories = [
            ['name' => 'list-3', 'parent_id' => 2], 
            ['name' => 'list-4', 'parent_id' => 1], 
            ['name' => 'list-5', 'parent_id' => 3], 
            ['name' => 'list-6', 'parent_id' => 4],

        ];
        
        Category::insert($categories);
    }
}
