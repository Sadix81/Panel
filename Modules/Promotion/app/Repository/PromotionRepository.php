<?php

namespace Modules\Promotion\Repository;

use Modules\Promotion\Models\Promotion;
use Modules\Property\Models\Property;

class PromotionRepository implements PromotionRepositoryInterface{
    public function allprductsdiscount($request) {
    
        $discountPromotion = Promotion::first();
    
        // اگر رکوردی وجود ندارد، یک رکورد جدید ایجاد می‌کنیم
        if (!$discountPromotion) {
            $discountPromotion = new Promotion();
        }
    
        // به‌روزرسانی یا ایجاد رکورد تخفیف
        $discountPromotion->type = $request->type ?? 'fixed';
        $discountPromotion->amount = $request->amount ?? 0;
        $discountPromotion->status = $request->status;
        $discountPromotion->save();
    
        $products = Property::all();
    
        // اگر تخفیف فعال است، قیمت محصولات را به‌روزرسانی می‌کنیم
        if ($request->status == 1) {
            foreach ($products as $product) {
                // ذخیره‌سازی قیمت و تخفیف‌های قبلی
                $product->previous_discounted_price = $product->discounted_price ?? $product->price;
                $product->previous_discount_type = $product->type; // ذخیره نوع تخفیف قبلی
                $product->previous_discount_amount = $product->amount; // ذخیره مقدار تخفیف قبلی
    
                $price = $product->price;
    
                if ($request->type == 'fixed') {
                    $final_price = $price - $request->amount;
                    if($request->amount >= $price){
                        return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
                    }
                } elseif ($request->type == 'percentage') {
                    $discountValue = ($price * $request->amount) / 100;
                    $final_price = $price - $discountValue;
                } else {
                    continue;
                }
                $product->update([
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'discounted_price' => $final_price,
                ]);
            }
        } else {
            // اگر تخفیف غیرفعال است، قیمت‌ها و تخفیف‌های قبلی را برمی‌گردانیم
            foreach ($products as $product) {
                // برگرداندن قیمت تخفیف‌خورده به قیمت قبلی
                $product->discounted_price = $product->previous_discounted_price;
                $product->type = $product->previous_discount_type; // برگرداندن نوع تخفیف
                $product->amount = $product->previous_discount_amount; // برگرداندن مقدار تخفیف
    
                // به‌روزرسانی ویژگی‌های محصول
                $product->update();
            }
        }
    }
}

