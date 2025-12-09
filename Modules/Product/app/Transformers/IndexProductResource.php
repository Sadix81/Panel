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
            'thumbnail' => $this->thumbnail ? asset('/'.$this->thumbnail) : null, // تولید آدرس کامل عکس
            'status' => $this->status,
            'price' => json_decode($this->properties->pluck('price')->unique()->values()->toJson()),
            'quantity' => $this->properties->pluck('quantity')->unique()->values(),
            'type' => $this->properties->pluck('type')->unique()->values(),
            'amount' => $this->properties->pluck('amount')->unique()->values(),
            'discounted_price' => $this->properties->pluck('discounted_price')->unique()->values(),
        ];
    }
}
