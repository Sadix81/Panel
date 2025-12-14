<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'thumbnail' => $this->thumbnail ?  $this->thumbnail : null, // تولید آدرس کامل عکس
            'description' => $this->description,
            'status' => $this->status,
            'category_id' => $this->categories->unique('id')->pluck('name', 'id')->map(function ($name) {
                return (object) ['name' => $name];
            })->values(),
            'price' => json_decode($this->properties->pluck('price')->unique()->values()->toJson()),
            'quantity' => $this->properties->pluck('quantity')->unique()->values(),
            'color' => $this->properties->pluck('color')->unique()->values(),
            'size' => $this->properties->pluck('size')->unique()->values(),
            'images' => $this->images->map(function ($image) {
                return [
                    'image_url' => $image->image_url,
                    'image_type' => $image->image_type,
                    'image_size' => $image->image_size,
                ];
            })->unique()->values(),
            'type' => $this->properties->pluck('type')->unique()->values(),
            'amount' => $this->properties->pluck('amount')->unique()->values(),
            'discounted_price' => $this->properties->pluck('discounted_price')->unique()->values(),
            // 'quantity' => $this->properties->pluck('quantity')->unique()->values(), //تعداد کمتر از شیش تا رو نشون بده روی بنر
        ];
    }
}
