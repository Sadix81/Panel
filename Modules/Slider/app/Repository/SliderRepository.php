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
                $path = $slider->store('sliders', 'public');
                Slider::create([
                    'slider_image_url' => $path,
                    'slider_image_type' => $slider->getClientMimeType(),
                    'slider_image_size' => $slider->getSize(),
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

            $data['slider_image_url'] = $newSliderImage->store('sliders', 'public');
            $data['slider_image_type'] = $newSliderImage->getClientMimeType();
            $data['slider_image_size'] = $newSliderImage->getSize();

            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        try {
            $slider->update($data);

            return null;
        } catch (\Exception $e) {
            Log::error('Error updating slider: '.$e->getMessage());

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
