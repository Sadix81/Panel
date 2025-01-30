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
            'thumbnail' => $this->thumbnail,
            //تخفیف
            'quantity' => $this->properties->pluck('quantity')->unique()->values(),
            'status' => $this->status,   
        ];
    }
}
