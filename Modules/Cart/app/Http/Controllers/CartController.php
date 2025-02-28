<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Http\Requests\AddToCartRequest;
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
            'error' => $error->original['error']
        ], 500);
    }
}
