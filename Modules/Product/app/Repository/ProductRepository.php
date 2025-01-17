<?php

namespace Modules\Product\Repository;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;

class ProductRepository implements ProductRepositoryInterface {

    public function index()
    {
        
    }

    public function store($request)
    {
        DB::beginTransaction();

        if (request()->hasFile('image')) {
            $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $image_name);
            $image_url = asset('images/' . $image_name);
        }

        try {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'Quantity' => $request->Quantity,
                'color' => $request->color,
                'description' => $request->description,
                // 'image_url' => $image_url,
                'is_active' => $request->is_active,
            ]);
            if ($request->has('category_id')) {
                $product->categories()->attach($request->category_id);
            }        
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($product , $request)
    {
        
    }

    public function delete($product)
    {
        
    }

    public function restore($product)
    {
        
    }
}