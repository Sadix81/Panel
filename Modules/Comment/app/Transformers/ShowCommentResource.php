<?php

namespace Modules\Comment\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  
            'text' => $this->text,  
            'product_id' => $this->product_id,  
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,  
        ];
    }
}
