<?php

namespace Modules\Discount\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowDiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discountname' => $this->name,
            'type' => $this->type,
            'amount' => $this->amount,
            'minimum_purchase' => $this->minimum_purchase,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'conditions' => $this->conditions,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'status' => $this->status,
        ];
    }
}
