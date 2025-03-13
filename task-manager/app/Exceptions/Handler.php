<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Validation failed',
                'details' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Resource not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            return response()->json([
                'status' => Response::HTTP_TOO_MANY_REQUESTS,
                'error' => 'Too many requests. Please try again later.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'error' => 'Something went wrong'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}