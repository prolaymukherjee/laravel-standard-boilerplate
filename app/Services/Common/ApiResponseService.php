<?php

namespace App\Services\Common;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    public static function success($data, $statusCode = 200): JsonResponse
    {
        $responseData = [
            'statusCode' => $statusCode,
            'error' => false,
            'errorMessage' => null,
            'errorBags' => null,
            'data' => $data,
        ];

        return response()->json($responseData, $statusCode);
    }

    public static function error($errorMessage, $statusCode = 400, $errorBags = null): JsonResponse
    {
        $errorResponseData = [
            'statusCode' => $statusCode,
            'error' => true,
            'errorMessage' => $errorMessage,
            'errorBags' => $errorBags,
            'data' => null,
        ];

        return response()->json($errorResponseData, $statusCode);
    }
}
