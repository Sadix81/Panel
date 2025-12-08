<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? asset('/'.$this->image) : null, // تولید آدرس کامل عکس
            'parent_id' => $this->parent_id,
            'parent_id_name' => $this->parent?->name,
            // Null-Safe Operator (?->) =>
            // If $this->parent is null, the ?-> operator will prevent the code from trying to access the name property,
            // and parent_id_name will simply be null.
        ];
    }
}
