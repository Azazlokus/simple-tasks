<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\Log;

trait HandlesApiResponse
{
    protected function successResponse(string $message, mixed $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function errorResponse(string $message, int $status = Response::HTTP_INTERNAL_SERVER_ERROR, Exception $e = null): JsonResponse
    {
        Log::error($message, ['exception' => $e ? $e->getMessage() : null]);

        return response()->json([
            'status' => $status,
            'error' => $message,
            'details' => $e ? $e->getMessage() : null
        ], $status);
    }
}