<?php

namespace Modules\Cart\Repository\Cart;

interface CartRepositoryInterface
{
    public function index();

    public function addToCart($request);

    public function updateCartQuantity($request);
    
    public function removeProduct($request);

    public function clearCart();
}
