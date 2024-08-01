<?php

declare(strict_types=1);

namespace App\Services\Api;

abstract class BaseService
{
    protected function __construct() {}

    public static function boot(...$dependencies)
    {
        return new static(...$dependencies);
    }
}
