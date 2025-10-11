<?php

namespace Modules\Slider\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Slider\Http\Requests\CreateSliderRequest;
use Modules\Slider\Models\Slider;
use Modules\Slider\Repository\SliderRepository;
use Modules\Slider\Transformers\IndexSliderResource;
use Modules\Slider\Transformers\ShowSliderResource;

class SliderController extends Controller
{
    private $sliderRepo;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepo = $sliderRepository;
    }

    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexSliderResource::collection($this->sliderRepo->index());
    }

    public function store(CreateSliderRequest $request)
    {
        $user = Auth::user();
        $existingSlidersCount = Slider::count();
        $newSlidersCount = count($request->file('slider_image_url'));

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($existingSlidersCount + $newSlidersCount > 4) {
            return ['error' => 'شما نمی‌توانید بیش از ۴ تصویر بارگذاری کنید.'];
        }

        $error = $this->sliderRepo->store($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.slider.create.success')], 200);
        } else {

            return response()->json(['message' => __('messages.slider.create.failed')], 500);
        }
    }

    public function show(Slider $slider)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return new ShowSliderResource($slider);
    }

    public function update(Slider $slider, Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->sliderRepo->update($slider, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.slider.update.success')], 200);
        } else {

            return response()->json(['message' => __('messages.slider.update.failed')], 500);
        }
    }

    public function destroy($slider)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        $error = $this->sliderRepo->delete($slider);

        if ($error === null) {
            return response()->json(['message' => __('messages.slider.delete.success')], 200);
        } else {
            return response()->json(['message' => __('messages.slider.delete.failed')], 500);
        }
    }
}
