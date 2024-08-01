<?php

namespace App\Dtos;

abstract class BaseDTO
{
    abstract public static function fromModel($model): self;

    abstract public static function fromRequest(array $validatedData): self;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
