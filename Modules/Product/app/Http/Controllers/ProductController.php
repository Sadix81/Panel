<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Http\Requests\CreateProductrequest;
use Modules\Product\Repository\ProductRepository;

class ProductController extends Controller
{
    private $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    public function index()
    {
        return view('product::index');
    }

    public function store(CreateProductrequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->productRepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.product.store.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.product.store.failed', ['name' => $request->name])], 500);
    }

    public function show($id)
    {
        return view('product::show');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
