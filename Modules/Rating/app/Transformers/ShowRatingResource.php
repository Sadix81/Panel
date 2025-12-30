<?php

namespace Modules\Rating\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowRatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'rate' => $this->rating,
            ],
        ];
    }
}
