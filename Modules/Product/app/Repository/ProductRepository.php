<?php

namespace Modules\Product\Repository;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Property\Models\Property;

class ProductRepository implements ProductRepositoryInterface {

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'category_id' => request()->has('category_id') ? request('category_id') : null,
            'search' => request()->has('search') ? request('search') : null,
            'price' => request()->has('price') ? request('price') : null,
            'status' => request()->has('status') ? 1 : null,
        ];

        try {
            $query = Product::query();

            if (!empty($req['status'])) {
                $query->where('status', $req['status']);
            }
    
            if (!empty($req['search'])) {
                $query->where('name', 'like', '%' . $req['search'] . '%');
            }
    
            // Filter by properties
            $query->whereHas('properties', function ($query) use ($req) {
                if (!empty($req['price'])) {
                    $query->where('price', '>=', $req['price']);
                }
                if (!empty($req['category_id'])) {
                    $query->where('category_id', $req['category_id']);
                }
            });
    
            $products = $query->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);
    
            return $products;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function store($request)
    {
        DB::beginTransaction();

        // if (request()->hasFile('image')) {
        //     $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
        //     $request->image->move(public_path('images'), $image_name);
        //     $image_url = asset('images/' . $image_name);
        // }
        

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                // 'image_url' => $image_url,
            ]);

            if ($request->has('category_id')) {
                $product->categories()->attach($request->category_id);
            }  


            $categoryIds = $request->category_id; // Array of category IDs
            $colorIds = $request->color_id; // Array of color IDs
            $sizeIds = $request->size_id; // Array of size IDs
            $combinations = [];
    
            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds)) {
                    // Both color and size are provided
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            $combinations[] = [
                                'category_id' => $categoryId,
                                'color_id' => $colorId,
                                'size_id' => $sizeId,
                            ];
                        }
                    }
                } elseif (is_array($colorIds)) {
                    // Only color is provided
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null, // Size is null
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    // Only size is provided
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // Color is null
                            'size_id' => $sizeId,
                        ];
                    }
                } else {
                    // Neither color nor size is provided
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                    ];
                }
            }
        
            // Insert combinations into the database
            foreach ($combinations as $combination) {
                Property::create([
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'category_id' => $combination['category_id'],
                    'color_id' => $combination['color_id'],
                    'size_id' => $combination['size_id'],
                    'product_id' => $request->product_id ?: $product->id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($product , $request)
    {
        DB::beginTransaction();

        // if (request()->hasFile('image')) {
        //     $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
        //     $request->image->move(public_path('images'), $image_name);
        //     $image_url = asset('images/' . $image_name);
        // }

        try {
            $product->update([
                'name' => $request->name ? $request->name : $product->name,
                'description' => $request->description ? $request->description : $product->description,
                'status' => $request->status ? $request->status : $product->status,
                // 'image_url' => $image_url,
            ]);
            if ($request->has('category_id')) {
                if (is_array($request->category_id) && !empty($request->category_id)) {
                    $validCategoryIds = array_filter($request->category_id, function ($id) { //filter invalid data
                        return is_numeric($id) && $id > 0;  //return valid data
                    });
            
                    if (!empty($validCategoryIds)) {
                        $product->categories()->sync($validCategoryIds);
                    }
                }
            }  

            $categoryIds = $request->category_id; // آرایه‌ای از IDهای دسته‌بندی
            $colorIds = $request->color_id; // آرایه‌ای از IDهای رنگ، می‌تواند null باشد
            $sizeIds = $request->size_id; // آرایه‌ای از IDهای سایز، می‌تواند null باشد

            if (!is_array($categoryIds)) {
                return response()->json(['message' => 'Invalid input. category_id must be an array.'], 400);
            }
            
            Property::where('product_id', $product->id)->delete();

            $combinations = [];
            foreach ($categoryIds as $categoryId) {
                if (is_array($colorIds) && is_array($sizeIds)) {
                    // هر دو رنگ و سایز موجود است
                    foreach ($colorIds as $colorId) {
                        foreach ($sizeIds as $sizeId) {
                            $combinations[] = [
                                'category_id' => $categoryId,
                                'color_id' => $colorId,
                                'size_id' => $sizeId,
                            ];
                        }
                    }
                } elseif (is_array($colorIds)) {
                    // فقط رنگ موجود است
                    foreach ($colorIds as $colorId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => $colorId,
                            'size_id' => null, // سایز null است
                        ];
                    }
                } elseif (is_array($sizeIds)) {
                    // فقط سایز موجود است
                    foreach ($sizeIds as $sizeId) {
                        $combinations[] = [
                            'category_id' => $categoryId,
                            'color_id' => null, // رنگ null است
                            'size_id' => $sizeId,
                        ];
                    }
                } else {
                    // هیچ‌کدام موجود نیستند
                    $combinations[] = [
                        'category_id' => $categoryId,
                        'color_id' => null,
                        'size_id' => null,
                    ];
                }
            }
        
            foreach ($combinations as $combination) {
                Property::create([
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'category_id' => $combination['category_id'],
                    'color_id' => $combination['color_id'],
                    'size_id' => $combination['size_id'],
                    'product_id' => $product->id,
                ]);
            }        
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete($product)
    {
        $product->delete();
    }
}