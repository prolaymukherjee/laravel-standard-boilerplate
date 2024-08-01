<?php

namespace App\Exceptions;

use App\Constants\Messages as MessagesConstant;
use App\Services\Common\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        HttpResponseException::class,
        ModelNotFoundException::class,
        AuthenticationException::class,
        ValidationException::class,
        HttpException::class,
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return ApiResponseService::error(
                MessagesConstant::RESOURCE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthenticationException) {
            return ApiResponseService::error(
                MessagesConstant::AUTHENTICATION_ERROR,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof ValidationException) {
            return ApiResponseService::error(
                MessagesConstant::VALIDATION_ERROR,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->errors()
            );
        }

        if ($exception instanceof HttpException) {
            return ApiResponseService::error(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        return ApiResponseService::error(
            MessagesConstant::INTERNAL_SERVER_ERROR,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return ApiResponseService::error(
            MessagesConstant::AUTHENTICATION_ERROR,
            Response::HTTP_UNAUTHORIZED
        );
    }
}
