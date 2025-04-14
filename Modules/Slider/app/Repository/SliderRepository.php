<?php

namespace Modules\Slider\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Slider\Models\Slider;

class SliderRepository implements SliderRepositoryInterface
{
    public function index()
    {
        $sliders = Slider::all();
        return $sliders;
    }

    public function store($request)
    {
        $sliders = $request->file('slider_image_url');
        if ($sliders) {
            foreach ($sliders as $slider) {
                $path = $slider->store('sliders', 'public');
                Slider::create([
                    'slider_image_url' => $path,
                    'slider_image_type' => $slider->getClientMimeType(),
                    'slider_image_size' => $slider->getSize(),
                ]);
            }
        }
    }

    public function update($request, $slider) 
    {

        if ($request->has('slider_image_url')) {
            $newSliderImage = $request->file('slider_image_url');
            $path = $newSliderImage->store('sliders', 'public');
        }
        $slider->update([
            'slider_image_url' => $path,
            'slider_image_type' => $newSliderImage->getClientMimeType(),
            'slider_image_size' => $newSliderImage->getSize(),
        ]);
    }

    public function delete($slider)
    {
        try {
            DB::beginTransaction();
            $slider->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting slider: ' . $e->getMessage());
            return response()->json(['message' => __('messages.slider.delete.failed')], 500);
        }
    }
}
