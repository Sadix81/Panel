<?php

namespace Modules\Category\Repository;

use Modules\Category\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface{

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $category = Category::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('name', 'like', '%'.$req['search'].'%');
            }
        })
        ->orderBy($req['sort'], $req['order'])
        ->paginate($req['limit']);
        
        return $category;

    }

    public function store($request)
    {
        if (request()->hasFile('image')) {
            $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $image_name);
        }

        $category = Category::create([
            'name' => $request->name,
            'image' => $request->image ? $image_name : null,
            'parent_id' => $request->parent_id,
        ]);
        $category->save();
    }

    public function update($category , $request)
    {
        $image_name = $category->image;

            // Check if an image has been uploaded
            if ($request->hasFile('image')) {
                $image_name = time().'-'.$request->title.'-'.$request->image->getClientOriginalName();
                $request->image->move(public_path('images'), $image_name);
            } elseif ($request->image === null) {
                $image_name = null;
                if ($category->image && file_exists(public_path('images/'.$category->image))) {
                    unlink(public_path('images/'.$category->image));
                }
            }

        $category->update([
            'name' => $request->name ? $request->name : $category->name,
            'image' => $image_name,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function delete($category)
    {
        $category = Category::find($category);

        if (!$category) {
            return response()->json(['message' => __('messages.category.not_found')], 404);
        }

        $all_categories_parent_id = Category::pluck('parent_id')->toArray(); //get all parent_id(s)

        if(in_array($category->id , $all_categories_parent_id)){
            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
        }
        $category->delete();
    }
}
