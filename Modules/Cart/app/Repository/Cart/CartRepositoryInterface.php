<?php

namespace Modules\Cart\Repository\Cart;

interface CartRepositoryInterface
{
    public function index();

    public function addToCart($request);

    // public function removeProduct($request);

    // public function updateQuantity($request);

    // public function clearCart($cart);
}