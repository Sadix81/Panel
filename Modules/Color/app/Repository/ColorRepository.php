<?php

namespace Modules\Color\Repository;

use Modules\Color\Models\Color;

class ColorRepository implements ColorRepositoryInterface
{
    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
            'code' => request()->has('code') ? request('code') : null,
        ];

        $color = Color::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('name', 'like', '%'.$req['search'].'%');
            }
            if ($req['code']) {
                $query->where('code', 'like', '%'.$req['code'].'%');
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $color;
    }

    public function store($request)
    {
        Color::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);
    }

    public function update($color, $request)
    {
        $color->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
    }

    public function delete($color)
    {
        $color = Color::find($color);

        if (! $color) {
            return response()->json(['message' => __('messages.color.not_found')], 404);
        }
        $color->delete();
    }
}
