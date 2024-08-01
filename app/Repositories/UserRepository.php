<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $__model;

    public function __construct(User $model)
    {
        $this->__model = $model;
    }

    public function createUser($userDTO): User
    {
        return $this->executeInTransaction(function () use ($userDTO) {

            $user = $this->__model->create([
                'name' => $userDTO->name,
                'email' => $userDTO->email,
                'password' => $userDTO->password,
                'role' => $userDTO->role,
            ]);

            return $user;
        });
    }
}
