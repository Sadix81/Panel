<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Http\Requests\AddToCartRequest;
use Modules\Cart\Http\Requests\RemoveCartItemRequest;
use Modules\Cart\Http\Requests\UpdatecartQuantityRequest;
use Modules\Cart\Models\CartItem;
use Modules\Cart\Repository\Cart\CartRepository;
use Modules\Cart\Transformers\IndexCartResource;

class CartController extends Controller
{
    private $cartRepo;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function create_cart()
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->cartRepo->create_cart();
        if ($error === null) {
            return response()->json(['message' => 'cart created successfully'], 201);
        }

        return response()->json(['message' => 'cart created failed'], 500);
    }

    public function index()
    {
        return IndexCartResource::collection($this->cartRepo->index());
    }

    public function addToCart(AddToCartRequest $request)
    {

        $error = $this->cartRepo->addToCart($request);

        if ($error === null) {
            return response()->json(['messages' => 'cart.AddToCart.success'], 200);
        }

        return response()->json([
            'messages' => 'cart.AddToCart.failed',
            'error' => $error->original['error'],
        ], 500);
    }

    public function updateCartQuantity(UpdatecartQuantityRequest $request){
        $error = $this->cartRepo->updateCartQuantity($request);
        if($error === null){
            return response()->json(['messages' => 'cart.updateCart.success'], 200);
        }
        return response()->json([
            'messages' => 'cart.updateCart.failed',
            'error' => $error->original['error'],
        ], 500);
    }

    public function removeProduct(RemoveCartItemRequest $request){
        $error = $this->cartRepo->removeProduct($request);
        if($error === null){
            return response()->json(['messages' => 'cart.removeitem.success'], 200);
        }
        return response()->json([
            'messages' => 'cart.removeitem.failed',
            'error' => $error->original['error'],
        ], 500);
    }
}
