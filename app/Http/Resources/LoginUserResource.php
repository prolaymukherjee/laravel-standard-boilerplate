<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//will have to work it later.
class LoginUserResource extends JsonResource
{
    public function toArray($user): array
    {
        return parent::toArray($user);
    }
}
