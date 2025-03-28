<?php

namespace Modules\Rating\Repository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Rating\Models\Rate;

class RatingRepository implements RatingRepositoryInterface
{

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'product_id' => request()->has('product_id') ? request('product_id') : null,
            'rate' => request()->has('rate') ? request('rate') : null,
        ];
        $rate = Rate::where(function ($query) use ($req) {
            if ($req['product_id']) {
                $query->where('product_id', $req['product_id']);
            }
            if ($req['rate']) {
                $query->where('rating', $req['rate']);
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $rate;
    }

    public function store($request)
    {
        $auth = Auth::id();

        $rate = Rate::where('user_id', $auth)
            ->where('product_id', $request->product_id)
            ->first();

        if ($rate) {
            return $this->update($rate,$request);
        }

        DB::beginTransaction();
        try {
            Rate::create([
                'rating' => $request->rating,
                'product_id' => $request->product_id,
                'user_id' => $auth,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($rate, $request)
    {
        $auth = Auth::id();

        $rate = Rate::where('user_id', $auth)
            ->where('product_id', $request->product_id)
            ->first();


        DB::beginTransaction();
        try {
            $rate->update([
                'rating' => $request->rating ? $request->rating : $rate->rating,
                'product_id' => $rate->product_id,
                'user_id' => $auth,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
