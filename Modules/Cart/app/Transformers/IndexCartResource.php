<?php

namespace Modules\Cart\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'total_price' => $this->total_price,
            'discounted_price' => $this->discounted_price,
            'cart_id' => $this->cartItems->pluck('cart_id')->unique()->values(),
            'product_id' => $this->cartItems->pluck('product_id'),
            'quantity' => $this->cartItems->pluck('quantity'),
        ];
    }

}
