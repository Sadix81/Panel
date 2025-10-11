<?php

namespace Modules\Rating\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Rating\Console\ProductRating;
use Modules\Rating\Http\Requests\RatingRequest;
use Modules\Rating\Models\Rate;
use Modules\Rating\Repository\RatingRepository;
use Modules\Rating\Transformers\IndexRatingResource;
use Modules\Rating\Transformers\ProductRatingResource;
use Modules\Rating\Transformers\ShowRatingResource;

class RatingController extends Controller
{
    private $rateRepo;

    public function __construct(RatingRepository $rateRepo)
    {
        $this->rateRepo = $rateRepo;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexRatingResource::collection($this->rateRepo->index());
    }

    public function store(RatingRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->rateRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => 'امتیاز محصول با موفقیت ثبت شد'], 201);
        }

        return response()->json(['message' => 'امتیاز محصول ثبت نشد'], 500);
    }

    public function show(Product $product)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $ratings = Rate::where('product_id', $product->id)
            ->select('id', 'rating', 'user_id', 'product_id')
            ->get();

        $finalAverageRating = $ratings->avg('rating');
        $userIds = $ratings->pluck('user_id')->toArray();

        $ratingResources = ShowRatingResource::collection($ratings);

        return response()->json([
            'average_rating' => $finalAverageRating,
            'ratings' => $ratingResources,
            'users_id' => $userIds,
        ]);
    }

    public function update(RatingRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $rate = Rate::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (! $rate) {
            return $this->store($request);
        }

        $error = $this->rateRepo->update($rate, $request);
        if ($error === null) {
            return response()->json(['message' => 'امتیاز محصول با موفقیت ویرایش شد'], 200);
        }

        return response()->json(['message' => 'امتیاز محصول ویرتیش نشد'], 500);
    }

    public function rateCalculate()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }
        ProductRating::dispatch();

        $ratings = DB::table('rate_products')->get();

        return ProductRatingResource::collection($ratings);
    }
}
