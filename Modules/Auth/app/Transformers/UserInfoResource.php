<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'lastname' => $this->lastname,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'twofactor' => $this->twofactor,
            'avatar' => $this->avatar ? $this->avatar : null, // تولید آدرس کامل عکس
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'address' => $this->address,
            'codepost' => $this->codepost,
        ];
    }
}
