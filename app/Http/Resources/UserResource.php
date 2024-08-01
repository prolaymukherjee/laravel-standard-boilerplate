<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($userDTO): array
    {
        return $this->resource->toArray();
    }
}
