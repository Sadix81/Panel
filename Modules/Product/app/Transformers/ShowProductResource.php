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
            'category_id' => $this->properties->pluck('category_id')->unique()->values(),
            'price' => $this->properties->pluck('price')->unique()->values(),
            'quantity' => $this->properties->pluck('quantity')->unique()->values(),
            'color' => $this->properties->pluck('color'),
            'size' => $this->properties->pluck('size'),
            'description' => $this->description,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail,
            'image_url' => $this->images->pluck('id')->unique()->values(),
            'type' => $this->properties->pluck('type')->unique()->values(),
            'amount' => $this->properties->pluck('amount')->unique()->values(),
            'discounted_price' => $this->properties->pluck('discounted_price')->unique()->values(),
            // 'quantity' => $this->properties->pluck('quantity')->unique()->values(), //تعداد کمتر از شیش تا رو نشون بده روی بنر
        ];
    }
}
