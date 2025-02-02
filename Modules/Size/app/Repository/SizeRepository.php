<?php

namespace Modules\Size\Repository;

use Modules\Size\Models\Size;

class SizeRepository implements SizeRepositoryInterface
{
    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $size = Size::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('title', 'like', '%'.$req['search'].'%');
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $size;
    }

    public function store($request)
    {
        Size::create([
            'title' => $request->title,
        ]);
    }

    public function update($size, $request)
    {
        $size->update([
            'title' => $request->title,
        ]);
    }

    public function delete($size)
    {
        $size = Size::find($size);

        if (! $size) {
            return response()->json(['message' => __('messages.size.not_found')], 404);
        }
        $size->delete();
    }
}
