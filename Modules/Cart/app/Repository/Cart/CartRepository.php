<?php

namespace Modules\Cart\Repository\Cart;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartItem;
use Modules\Product\Models\Product;
use Illuminate\Support\Str;
use Modules\Property\Models\Property;

class CartRepository implements CartRepositoryInterface{

    public function create_cart(){
        $auth = Auth::id();

        $cart = Cart::where('user_id' , $auth)->first();
        if ($cart) {
            return response()->json(['message' => 'سبد خرید قبلاً ایجاد شده است.'], 200);
        }

        Cart::create([
            'user_id' => $auth
        ]);
    }

    public function index()
    {
        $auth = Auth::id();

        if (!$auth) {
            // return response()->json(['message' => 'User not authenticated'], 401);
        }
        $cart = Cart::where('user_id', $auth)->first();
    
        if (!$cart) {
            return response()->json(['message' => 'No cart found for this user'], 404);
        }

        return $cart;
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


        if (!$auth) {
            if (!isset($_COOKIE['uuid'])) {
                $guestCartId = Str::uuid()->toString();
                setcookie('uuid', $guestCartId, time() + (86400 * 30), "/"); //30 days
            } else {
                $guestCartId = $_COOKIE['uuid'];
            }
            
            $cart = Cart::where('uuid', $guestCartId)->first();
        }

        if($auth){
            $cart = Cart::where('user_id', $auth)->first();
        }

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $auth,
                'uuid' => $guestCartId ?? null, // ذخیره uuid در صورت مهمان
            ]);
        }
        
        $cart_id = $cart->id;

        $property = Property::where('product_id' , $request->product_id)
        ->where('color_id', $request->color_id)
        ->where('size_id', $request->size_id)
        ->first();

        if (!$property) {
            return response()->json(['error' => '.محصول مورد نظر یافت نشد'], 404);
        }

        if ($property->quantity < $request->quantity) {
            return response()->json(['error' => '.مفدار مورد نظر موجود نمی باشد'], 400);
        }

        DB::beginTransaction();
        
        try {
            // $cartItem = $cart->cartItems()->where('property_id', $property->id)->first();
            // if ($cartItem) {
            //     $newQuantity = $cartItem->quantity += $request->quantity;
            //     if ($newQuantity < 0) {
            //         return response()->json(['error' => '.مقدار نمی‌تواند منفی باشد'], 400);
            //     }
            //     $cartItem->save();
            // } else {
            $cart->cartItems()->create([
                'product_id' => $property->id,
                'quantity' => $request->quantity,
                'cart_id' => $cart_id 
                // 'price' => $property->pricediscounted_price ? $property->pricediscounted_price : $property->price,
            ]);

            // }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback the transaction if there's an error
            Log::error('Error adding product to cart: ' . $th->getMessage());
            return response()->json(['error' =>  $th->getMessage()], 500);
        }
 
    }

    public function removeProduct($request)
    {
//
    }

    public function updateQuantity($request)
    {

    }

    public function calculateTotal()
    {

    }

    public function clearCart($cart)
    {

    }
}