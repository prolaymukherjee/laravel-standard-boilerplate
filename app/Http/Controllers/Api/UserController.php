<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Common\ApiResponseService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $_userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->_userService = $userService;
    }

    public function store(UserRequest $userRequest)
    {
        $user = $this->_userService->createUser($userRequest->validated());

        return ApiResponseService::success(
            new UserResource($user),
            Response::HTTP_CREATED
        );
    }
}
