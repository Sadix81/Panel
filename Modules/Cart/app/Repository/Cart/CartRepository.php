<?php

namespace Modules\Cart\Repository\Cart;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartItem;
use Modules\Property\Models\Property;

class CartRepository implements CartRepositoryInterface
{
    public function create_cart()
    {
        $auth = Auth::id();

        $cart = Cart::where('user_id', $auth)->first();
        if ($cart) {
            return response()->json(['message' => 'سبد خرید قبلاً ایجاد شده است.'], 200);
        }

        Cart::create([
            'user_id' => $auth,
        ]);
    }

    public function index()
    {
        $auth = null;
        $user = Auth::guard('api')->user();
        if ($user) {
            $auth = $user->id;
        }

        $guestCartId = null;
        if (! $auth) {
            $guestCartId = $_COOKIE['uuid'];
        }

        $carts = Cart::where('user_id', $auth)
        ->orWhere('uuid', $guestCartId)
            ->whereHas('cartItems')
            ->get();

        return $carts;
    }

    public function addToCart($request)
    {
        $auth = null;
        $user = Auth::guard('api')->user();
        // Check if the user is authenticated
        if ($user) {
            $auth = $user->id;
        }

        $guestCartId = null;
        $total_price = 0;
        $discounted_price = 0;
        $final_price = 0;

        if (! $auth) {
            if (! isset($_COOKIE['uuid'])) {
                $guestCartId = Str::uuid()->toString();
                setcookie('uuid', $guestCartId, time() + (86400 * 30), '/'); // 30 days
            } else {
                $guestCartId = $_COOKIE['uuid'];
            }

            $cart = Cart::where('uuid', $guestCartId)->first();
        }

        if ($auth) {
            $cart = Cart::where('user_id', $auth)->first();
        }

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => $auth,
                'uuid' => $guestCartId ?? null, // ذخیره uuid در صورت مهمان
            ]);
        }

        $cart_id = $cart->id;

        $property = Property::where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (! $property) {
            return response()->json(['error' => '.محصول مورد نظر یافت نشد'], 404);
        }

        if ($property->quantity < $request->quantity) {
            return response()->json(['error' => '.مفدار مورد نظر موجود نمی باشد'], 400);
        }

        DB::beginTransaction();

        try {
            $cartItem = $cart->cartItems()->where('property_id', $property->id)->first();
            if ($cartItem) {
                $newQuantity = $cartItem->quantity = $request->quantity;
                if ($newQuantity < 0) {
                    return response()->json(['error' => '.مقدار نمی‌تواند منفی باشد'], 400);
                }
                if ($newQuantity > $property->quantity) {
                    return response()->json(['error' => '.مفدار مورد نظر موجود نمی باشد'], 400);
                }
                $cartItem->save();
            } else {
                $cart->cartItems()->create([
                    'property_id' => $property->id,
                    'product_id' => $property->product_id,
                    'quantity' => $request->quantity,
                    'cart_id' => $cart_id,
                ]);
            }

            foreach ($cart->cartItems as $item) {
                $property = Property::find($item->property_id);
                if ($property->discounted_price) {
                    $discounted_price += $property->discounted_price * $item->quantity;
                }
                $total_price += $property->price * $item->quantity;
            }
            $final_price = $total_price - $discounted_price;
            $cart->update([
                'total_price' => $total_price,
                'discounted_price' => $discounted_price,
                'final_price' => $final_price,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction if there's an error
            Log::error('Error adding product to cart: ' . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function updateCartQuantity($request)
    {
        $auth = null;
        $user = Auth::guard('api')->user();
        if ($user) {
            $auth = $user->id;
        }

        $guestCartId = null;
        $discounted_price = 0;
        $total_price = 0;

        if (! $auth) {
            $guestCartId = $_COOKIE['uuid'];
            if ($guestCartId) {
                $cart = Cart::where('uuid', $guestCartId)->first();
            }
        } else {
            $cart = Cart::where('user_id', $auth)->first();
        }

        $property = Property::where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (! $property) {
            return response()->json(['error' => '.محصول مورد نظر یافت نشد'], 404);
        }

        if ($property->quantity < $request->quantity) {
            return response()->json(['error' => '.مفدار مورد نظر موجود نمی باشد'], 400);
        }

        try {
            $cartItem = $cart->cartItems()->where('property_id', $property->id)->first();
            if ($cartItem) {
                $newQuantity = $cartItem->quantity = $request->quantity;
                if ($newQuantity < 0) {
                    return response()->json(['error' => '.مقدار نمی‌تواند منفی باشد'], 400);
                }
                if ($newQuantity > $property->quantity) {
                    return response()->json(['error' => '.مفدار مورد نظر موجود نمی باشد'], 400);
                }
                $cartItem->save();
            }

            foreach ($cart->cartItems as $item) {
                $property = Property::find($item->property_id);
                if ($property->discounted_price) {
                    $discounted_price += $property->discounted_price * $item->quantity;
                }
                $total_price += $property->price * $item->quantity;
            }
            $final_price = $total_price - $discounted_price;

            $cart->update([
                'total_price' => $total_price,
                'discounted_price' => $discounted_price,
                'final_price' => $final_price,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error adding product to cart: ' . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function removeProduct($request)
    {
        $auth = null;
        $user = Auth::guard('api')->user();
        if ($user) {
            $auth = $user->id;
        }

        $guestCartId = null;
        $discounted_price = 0;
        $total_price = 0;

        if (! $auth) {
            $guestCartId = $_COOKIE['uuid'];
            if ($guestCartId) {
                $cart = Cart::where('uuid', $guestCartId)->first();
            }
        } else {
            $cart = Cart::where('user_id', $auth)->first();
        }

        $properties = $cart->cartItems()->pluck('property_id')->toArray();

        try {
            $item = $request->property_id;
            if (in_array($item, $properties)) {

                $cart->cartItems()->where('property_id', $item)->delete();

                foreach ($cart->cartItems as $item) {
                    $property = Property::find($item->property_id);
                    if ($property->discounted_price) {
                        $discounted_price += $property->discounted_price * $item->quantity;
                    }
                    $total_price += $property->price * $item->quantity;
                }
                $final_price = $total_price - $discounted_price;

                $cart->update([
                    'total_price' => $total_price,
                    'discounted_price' => $discounted_price,
                    'final_price' => $final_price,
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error adding product to cart: ' . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function clearCart()
    {
        $auth = null;
        $user = Auth::guard('api')->user();
        if ($user) {
            $auth = $user->id;
        }

        $guestCartId = null;

        if (! $auth) {
            $guestCartId = $_COOKIE['uuid'];
            if ($guestCartId) {
                $cart = Cart::where('uuid', $guestCartId)->first();
            }
        } else {
            $cart = Cart::where('user_id', $auth)->first();
        }

        $items = $cart->cartItems()->get();
        try {
            if($items){
                $cart->cartItems()->delete();
    
                $cart->update([
                    'total_price' => 0,
                    'discounted_price' => 0,
                    'final_price' => 0,
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error adding product to cart: ' . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
