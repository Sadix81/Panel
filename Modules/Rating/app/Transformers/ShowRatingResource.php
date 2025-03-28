<?php

namespace Modules\Rating\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowRatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'rating' => $this->rating,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
        ];
    }
}
