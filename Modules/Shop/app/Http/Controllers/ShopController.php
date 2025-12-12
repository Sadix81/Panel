<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Http\Requests\UpdateShopRequest;
use Modules\Shop\Models\Shop;
use Modules\Shop\Repository\ShopRepository;
use Modules\Shop\Transformers\IndexShopResource;

class ShopController extends Controller
{
    private $shopRepo;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepo = $shopRepository;
    }
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexShopResource::collection($this->shopRepo->index());
    }


public function update(Shop $shop, UpdateShopRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->shopRepo->update($shop, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.shop.update.success')], 200);
        }

        return response()->json(['message' => __('messages.shop.update.failed')], 500);
    }

}
