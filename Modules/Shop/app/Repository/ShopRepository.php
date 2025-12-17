<?php

namespace Modules\Shop\Repository;

use Modules\Shop\Models\Shop;

class ShopRepository implements ShopRepositoryInterface
{
    public function index()
    {
        $shop = Shop::all();

        return $shop;
    }

    public function update($shop, $request)
    {
        $shop->update([
            'name' => $request->name ? $request->name : $shop->name,
            'telephone' => $request->telephone ? $request->telephone : $shop->telephone,
            'email' => $request->email ? $request->email : $shop->email,
            'country' => $request->country ? $request->country : $shop->country,
            'province' => $request->province ? $request->province : $shop->province,
            'city' => $request->city ? $request->city : $shop->city,
            'address' => $request->address ? $request->address : $shop->address,
            'codepost' => $request->codepost ? $request->codepost : $shop->codepost,
        ]);

    }
}
