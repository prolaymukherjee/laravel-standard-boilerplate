<?php

namespace App\Services\Api;

use App\Contracts\Services\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService implements AuthServiceInterface
{
    public function login(array $credentials): ?User
    {
        if (! $token = JWTAuth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $user->token = $token;

        return $user;
    }
}
