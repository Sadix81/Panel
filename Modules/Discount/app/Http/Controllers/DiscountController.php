<?php

namespace Modules\Discount\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Discount\Http\Requests\CreateDiscountRequest;
use Modules\Discount\Http\Requests\UpdateDiscountRequest;
use Modules\Discount\Models\Discount;
use Modules\Discount\Repository\DiscountRepository;
use Modules\Discount\Transformers\IndexDiscountResource;
use Modules\Discount\Transformers\ShowDiscountResource;

class DiscountController extends Controller
{
    private $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexDiscountResource::collection($this->discountRepository->index());
    }

    public function store(CreateDiscountRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($request->type == 'percentage' && $request->amount >= 100) {
            return response()->json(['message' => 'نمیتوان صد در صد تخفیف اعمال کرد']);
        }

        if ($request->start_date < $request->end_date) {
            return response()->json(['message' => 'تاریخ انقضای کد نادرست است']);
        }

        $error = $this->discountRepository->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.discount.store.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.discount.store.failed', ['name' => $request->name])], 500);
    }

    public function show(Discount $discount)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowDiscountResource($discount);

    }

    public function update(Discount $discount, UpdateDiscountRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($request->type == 'percentage' && $request->amount >= 100) {
            return response()->json(['message' => 'نمیتوان صد در صد تخفیف اعمال کرد']);
        }

        if ($request->start_date < $request->end_date) {
            return response()->json(['message' => 'تاریخ انقضای کد نادرست است']);
        }

        $error = $this->discountRepository->update($discount, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.discount.update.success', ['name' => $discount->name])], 200);
        }

        return response()->json(['message' => __('messages.discount.update.failed', ['name' => $discount->name])], 500);
    }

    public function destroy($discount)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->discountRepository->delete($discount);
        if ($error === null) {
            return response()->json(['message' => __('messages.disc$discount.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.disc$discount.delete.failed')], 500);
    }
}
