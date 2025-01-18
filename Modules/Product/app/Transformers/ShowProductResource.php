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
            'category_id' => $this->categories->pluck('id'),
            'price' => $this->price,
            'Quantity' => $this->Quantity, //تعداد موجودی
            'color' => $this->color,
            'description' => $this->description,
            // 'image_url' => $this->image_url,
            'is_active' => $this->is_active,   
        ];
    }
}
