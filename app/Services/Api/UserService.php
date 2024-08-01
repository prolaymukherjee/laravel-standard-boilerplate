<?php

namespace App\Services\Api;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Dtos\UserDTO;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService implements UserServiceInterface
{
    protected $__userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->__userRepository = $userRepository;
    }

    public function createUser($createUserRequest): ?UserDTO
    {
        $userDTO = UserDTO::fromRequest($createUserRequest);

        $userDTO->password = Hash::make($userDTO->password);

        $user = $this->__userRepository->createUser($userDTO);

        return UserDTO::fromModel($user);
    }
}
