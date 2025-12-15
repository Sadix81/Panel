<?php

namespace Modules\Weight\Repository;

use Modules\Weight\Models\Weight;

class WeightRepository implements WeightRepositoryInterface{

    public function index(){
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $weight = Weight::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('title', 'like', '%'.$req['search'].'%');
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $weight;
    }

    public function store($request){
        Weight::create([
            'title' => $request->title,
            'weight_value' => $request->weight_value,
        ]);
    }

    public function update($weight , $request){
        $weight->update([
            'title' => $request->title ? $request->title : $weight->title,
            'weight_value' => $request->weight_value ? $request->weight_value : $weight->weight_value
        ]);
    }

    public function delete($weight){

        $weight = Weight::find($weight);

        if (! $weight) {
            return response()->json(['message' => __('messages.weight.not_found')], 404);
        }
        $weight->delete();
    }
}
