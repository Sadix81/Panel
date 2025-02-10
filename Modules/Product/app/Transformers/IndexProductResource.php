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
            'quantity' => $this->properties->pluck('quantity')->unique()->values(),
            'status' => $this->status,
            'type' => $this->properties->pluck('type')->unique()->values(),
            'amount' => $this->properties->pluck('amount')->unique()->values(),
            'discounted_price' => $this->properties->pluck('discounted_price')->unique()->values(),
        ];
    }
}
