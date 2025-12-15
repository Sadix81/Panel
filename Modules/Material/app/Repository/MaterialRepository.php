<?php

namespace Modules\Material\Repository;

use Modules\Material\Models\Material;

class MaterialRepository implements MaterialRepositoryInterface{

     public function index(){
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $material = Material::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('title', 'like', '%'.$req['search'].'%');
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $material;
    }

    public function store($request){
        Material::create([
            'title' => $request->title,
        ]);
    }

    public function update($material , $request){
        $material->update([
            'title' => $request->title ? $request->title : $material->title,
        ]);
    }

    public function delete($material){

        $material = Material::find($material);

        if (! $material) {
            return response()->json(['message' => __('messages.material.not_found')], 404);
        }
        $material->delete();
    }
}
