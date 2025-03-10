<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Http\Requests\CreateProductrequest;
use Modules\Product\Http\Requests\UpdateProductrequest;
use Modules\Product\Models\Product;
use Modules\Product\Repository\ProductRepository;
use Modules\Product\Transformers\IndexProductResource;
use Modules\Product\Transformers\ShowProductResource;

class ProductController extends Controller
{
    private $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexProductResource::collection($this->productRepo->index());
    }

    public function store(CreateProductrequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($request->price <= 0) {
            return response()->json(['message' => 'قیمت محصول باید بزرگتر از صفر باشد']);
        }

        if ($request->quantity <= 0) {
            return response()->json(['message' => 'مقدار موجودی باید بزرگتر از صفر باشد']);
        }

        if (! $request->category_id) {
            return response()->json(['message' => 'انتخاب حداقل یک دسته بندی الزامیست']);
        }

        if ($request->type && ! $request->amount) {
            return response()->json(['message' => 'وارد کردن مقدار تخفیف الزامیست']);
        }

        if ($request->type && $request->amount && $request->amount <= 0) {
            return response()->json(['message' => 'مفدار تخفیف باید بزرگتر از صفر باشد']);
        }

        if ($request->type == 'percentage' && $request->amount >= '100') {
            return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
        }

        if ($request->type == 'fixed' && $request->amount >= $request->price) {
            return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
        }

        $error = $this->productRepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.product.store.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.product.store.failed', ['name' => $request->name])], 500);
    }

    public function show(Product $product)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowProductResource($product);
    }

    public function update(Product $product, UpdateProductrequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($request->price <= 0) {
            return response()->json(['message' => 'قیمت محصول باید بزرگتر از صفر باشد']);
        }

        if ($request->quantity <= 0) {
            return response()->json(['message' => 'مقدار موجودی باید بزرگتر از صفر باشد']);
        }

        if (! $request->category_id) {
            return response()->json(['message' => 'انتخاب حداقل یک دسته بندی الزامیست']);
        }

        if ($request->type && ! $request->amount) {
            return response()->json(['message' => 'وارد کردن مقدار تخفیف الزامیست']);
        }

        if ($request->type && $request->amount && $request->amount <= 0) {
            return response()->json(['message' => 'مفدار تخفیف باید بزرگتر از صفر باشد']);
        }

        if ($request->type == 'percentage' && $request->amount >= '100') {
            return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
        }

        if ($request->type == 'fixed' && $request->amount >= $request->price) {
            return response()->json(['message' => 'نمیتوان صددرصد تخفیف اعمال کرد']);
        }

        $error = $this->productRepo->update($product, $request);

        if ($error === null) {
            return response()->json(['message' => __('messages.product.update.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.product.update.failed', ['name' => $request->name])], 500);
    }

    public function thumbnail(Product $product)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }
        $error = $this->productRepo->thumbnail($product);
        if ($error === null) {
            return response()->json(['message' => __('messages.product.thumbnail.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.product.thumbnail.delete.failed')], 500);
    }

    public function product_iamge(Product $product)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }
        $error = $this->productRepo->product_iamge($product);
        if ($error === null) {
            return response()->json(['message' => __('messages.product.thumbnail.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.product.thumbnail.delete.failed')], 500);
    }

    public function destroy(Product $product)
    {
        $auth = Auth::id();

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->productRepo->delete($product);
        if ($error === null) {
            return response()->json(['message' => __('messages.product.delete.success')], 200);
        }

        return response()->json(['message' => __('messages.product.delete.failed')], 500);
    }
}
