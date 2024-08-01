<?php

namespace App\Http\Controllers\Api;

use App\Constants\Messages as MessagesConstant;
use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Common\ApiResponseService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $__authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->__authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->__authService->login($request->validated());

        if (! ($user)) {
            return ApiResponseService::error(MessagesConstant::NOT_MATCH_ERROR, Response::HTTP_UNAUTHORIZED);
        }

        return ApiResponseService::success(
            new UserResource($user),
            Response::HTTP_CREATED
        );
    }
}
