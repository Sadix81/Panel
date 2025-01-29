<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->properties->pluck('price')->unique()->values(),
            // 'image_url' => $this->image_url,
            //تخفیف
            // 'quantity' => $this->properties->pluck('quantity')->unique()->values(), //تعداد کمتر از شیش تا رو نشون بده روی بنر 
            
            // 'category_id' => $this->properties->pluck('category_id')->unique()->values(),
            // 'color' => $this->properties->pluck('color')->unique()->values(),
            // 'size' => $this->properties->pluck('size')->unique()->values(),
            // 'description' => $this->description,
            // 'status' => $this->status,   
        ];
    }
}
