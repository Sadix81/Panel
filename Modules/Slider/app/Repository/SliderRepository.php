<?php

namespace Modules\Slider\Repository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
                $mimeType = $slider->getClientMimeType();
                $size = $slider->getSize(); // اندازه فایل

                $image_name = time() . '-' . $slider->getClientOriginalName();
                $relative_path = 'images/sliders/' . $image_name;

                $slider->move(public_path('images/sliders'), $image_name);

                Slider::create([
                    'slider_image_url' => $relative_path,
                    'slider_image_type' => $mimeType,
                    'slider_image_size' => $size,
                ]);
            }
        }
    }

    public function update($slider, $request)
    {
        $oldImagePath = $slider->slider_image_url;
        $data = [
            'slider_image_url' => $oldImagePath,
            'slider_image_type' => $slider->slider_image_type,
            'slider_image_size' => $slider->slider_image_size,
        ];

        if ($request->hasFile('slider_image_url')) {
            $newSliderImage = $request->file('slider_image_url');

        // ایجاد نام یونیک برای تصویر
        $image_name = time() . '-' . $newSliderImage->getClientOriginalName();
        $data['slider_image_url'] = 'images/sliders/' . $image_name;

        // انتقال تصویر به مسیر مورد نظر
        $newSliderImage->move(public_path('images/sliders'), $image_name);

        try {
            $slider->update($data);

            return null;
        } catch (\Exception $e) {
            Log::error('Error updating slider: '.$e->getMessage());

            return 'Update failed';
        }
    }
        try {
        $slider->update($data);
        return null;
    } catch (\Exception $e) {
        Log::error('Error updating slider: ' . $e->getMessage());
        return 'Update failed';
    }
    }

    public function delete($slider)
    {
        $slider = Slider::find($slider);

        if (! $slider) {
            return response()->json(['message' => __('messages.size.not_found')], 404);
        }
        $slider->delete();
    }
}
