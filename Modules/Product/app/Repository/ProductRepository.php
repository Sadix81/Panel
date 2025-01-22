<?php

namespace Modules\Product\Repository;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;

class ProductRepository implements ProductRepositoryInterface {

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'category' => request()->has('category') ? request('category') : null,
            'search' => request()->has('search') ? request('search') : null,
            'price' => request()->has('price') ? request('price') : null,
            'status' => request()->has('status') ? 1 : null,
        ];

        try {
            $product = Product::whereHas('categories', function ($query) use ($req) {
                if ($req['category']) {
                    $query->where('category_id', $req['category']);
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['search']) {
                    $query->where('name', 'Like', '%'.$req['search'].'%');
                }
                // if ($req['price']) {
                //     $query->where('price', '>=', $req['price']);
                // }
                if ($req['status']) {
                    $query->where('status', 1)
                    ->where('Quantity' , '>' , 0);
                }
                })
                ->orderBy($req['sort'], $req['order'])
                ->paginate($req['limit']);

            return $product;
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
                // 'price' => $request->price,
                // 'Quantity' => $request->Quantity,
                // 'color' => $request->color,
                // 'image_url' => $image_url,
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
                // 'price' => $request->price ? $request->price : $product->price,
                // 'Quantity' => $request->Quantity ? $request->Quantity : $product->Quantity,
                // 'color' => $request->color ? $request->color : $product->color,
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
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($product)
    {
        $product->delete();
    }
}