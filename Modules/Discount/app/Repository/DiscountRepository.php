<?php

namespace Modules\Discount\Repository;

use Carbon\Carbon;
use Modules\Discount\Models\Discount;
use Modules\Property\Models\Property;

class DiscountRepository implements DiscountRepositoryInterface{

    public function index()
    {
        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
            'type' => request()->has('type') ? request('type') : null,
            'amount' => request()->has('amount') ? request('amount') : null,
            'start_date' => request()->has('start_date') ? request('start_date') : null,
            'status' => request()->has('status') ? request('status') : null,
        ];

        $category = Discount::where(function ($query) use ($req) {
            if ($req['search']) {
                $query->where('name', 'like', '%'.$req['search'].'%');
            }
            if ($req['type']) {
                $query->where('type' , $req['type']);
            }
            if ($req['amount']) {
                $query->where('amount' , '>=' , $req['amount']);
            }
            if ($req['start_date']) {
                $query->where('start_date' , $req['start_date']);
            }
            if ($req['status']) {
                $query->where('status' , 1);
            }
        })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $category;
    }

    public function store($request)
    {
        Discount::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'minimum_purchase' => $request->minimum_purchase,
            'start_date' => $request->start_date ? $request->start_date : Carbon::today(),
            'end_date' => $request->end_date,
            'conditions' => $request->conditions,
            'usage_limit' => $request->usage_limit,
            'used_count' => $request->used_count,
            'status' => $request->status !== null ? $request->status : 1
        ]);
        // dd($request->all());
    }

    public function update($discount , $request)
    {
        $discount->update([
            'name' => $request->name ? $request->name : $discount->name,
            'type' => $request->type ? $request->type : $discount->type,
            'amount' => $request->amount ? $request->amount : $discount->amount,
            'minimum_purchase' => $request->minimum_purchase ? $request->minimum_purchase : $discount->minimum_purchase,
            'start_date' => $request->start_date ? $request->start_date : $discount->start_date,
            'end_date' => $request->end_date ? $request->end_date : $discount->end_date,
            'conditions' => $request->conditions ? $request->conditions : $discount->conditions,
            'usage_limit' => $request->usage_limit ? $request->usage_limit : $discount->usage_limit,
            'used_count' => $request->used_count ? $request->used_count : $discount->used_count,
            'status' => $request->status !== null ? $request->status : $discount->status
        ]);
    }

    public function delete($discount)
    {
        $discount = Discount::find($discount);

        if (! $discount) {
            return response()->json(['message' => __('messages.dis$discount.not_found')], 404);
        }

        $discount->delete();
    }
}