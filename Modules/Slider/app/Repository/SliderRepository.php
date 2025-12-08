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
        try {
            $data = [
                'slider_image_type' => $slider->slider_image_type,
                'slider_image_size' => $slider->slider_image_size,
            ];

            if ($request->hasFile('slider_image_url')) {
                if ($slider->slider_image_url) {
                    $oldImagePath = public_path($slider->slider_image_url);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $newSliderImage = $request->file('slider_image_url');
                $mimeType = $newSliderImage->getClientMimeType();
                $image_name = time() . '-' . $newSliderImage->getClientOriginalName();
                $relative_path = 'images/sliders/' . $image_name;

                // ذخیره تصویر جدید
                $newSliderImage->move(public_path('images/sliders'), $image_name);

                $data['slider_image_url'] = $relative_path;
                $data['slider_image_type'] = $mimeType;
                $data['slider_image_size'] = filesize(public_path($relative_path)); // استفاده از filesize برای به دست آوردن اندازه
            }

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
