<?php

namespace Modules\Promotion\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Promotion\Http\Requests\DiscountallProductRequest;
use Modules\Promotion\Repository\PromotionRepository;

class PromotionController extends Controller
{
    private $promotionRepo;

    public function __construct(PromotionRepository $promotionRepo)
    {
        $this->promotionRepo = $promotionRepo;
    }

    public function allprductsdiscount(DiscountallProductRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($request->type && ! $request->amount) {
            return response()->json(['message' => 'وارد کردن مقدار تخفیف الزامیست']);
        }

        if ($request->type && $request->amount && $request->amount <= 0) {
            return response()->json(['message' => 'مفدار تخفیف باید بزرگتر از صفر باشد']);
        }

        if ($request->type == 'percentage' && $request->amount >= 100) {
            return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
        }

        $error = $this->promotionRepo->allprductsdiscount($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.discountAllProducts.update.success')], 200);
        }

        return response()->json(['message' => __('messages.discountAllProducts.update.failed')], 500);
    }
}
