<?php

namespace Modules\Favorite\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Favorite\Models\Favorite;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function index()
    {
        $user_id = Auth::id();
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $favorite = Favorite::where('user_id', $user_id)
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $favorite;
    }

    public function store($request)
    {
        $user_id = Auth::id();

        $favorite = Favorite::where('user_id', $user_id)
            ->where('product_id', $request->product_id)->first();
        if ($favorite) {
            return 'exist';
        }

        DB::beginTransaction();

        try {
            Favorite::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            Log::error('Error adding product to favorites: '.$th->getMessage());
        }
    }

    public function remove($favorite)
    {
        DB::beginTransaction();

        try {
            $favorite->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
