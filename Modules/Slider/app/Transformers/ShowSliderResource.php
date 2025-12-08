<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slider_image_url' => $this->slider_image_url ? asset('/' . $this->slider_image_url) : null, // تولید آدرس کامل عکس
            'slider_image_type' => $this->slider_image_type,
            'slider_image_size' => $this->slider_image_size,
        ];
    }
}
