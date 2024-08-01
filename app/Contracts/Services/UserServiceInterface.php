<?php

namespace App\Contracts\Services;

use App\Dtos\UserDTO;

interface UserServiceInterface
{
    public function createUser($createUserRequest): ?UserDTO;
}
