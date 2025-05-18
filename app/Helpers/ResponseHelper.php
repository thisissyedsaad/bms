<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Throwable;

class ResponseHelper
{
    /**
     * Generate a standardized API response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @param Throwable|string|null $error
     * @return JsonResponse
     */
    public static function apiResponse(
        mixed $data = null,
        string $message = '',
        int $statusCode = 200,
        Throwable|string|null $error = null
    ): JsonResponse {
        $response = [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => $message,
            'data' => $data,
        ];

        if ($error && config('app.env') !== 'production') {
            $response['error'] = [
                'message' => $error instanceof Throwable ? $error->getMessage() : $error,
                'file' => $error instanceof Throwable ? $error->getFile() : null,
                'line' => $error instanceof Throwable ? $error->getLine() : null,
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Generate a success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return self::apiResponse($data, $message, $statusCode);
    }

    /**
     * Generate an error response
     *
     * @param string $message
     * @param int $statusCode
     * @param Throwable|string|null $error
     * @return JsonResponse
     */
    public static function errorResponse(
        string $message = 'Error occurred',
        int $statusCode = 500,
        Throwable|string|null $error = null
    ): JsonResponse {
        return self::apiResponse(null, $message, $statusCode, $error);
    }

    /**
     * Generate a validation error response
     *
     * @param Validator|MessageBag $validator
     * @param string|null $message
     * @return JsonResponse
     */
    public static function validationErrorResponse(Validator|MessageBag $validator, ?string $message = null): JsonResponse
    {
        $errors = $validator instanceof Validator ? $validator->errors() : $validator;
        
        return self::apiResponse(
            [
                'errors' => $errors->toArray(),
            ],
            $message ?? 'Validation failed',
            422
        );
    }

    /**
     * Generate a not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return self::apiResponse(null, $message, 404);
    }

    /**
     * Generate an unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return self::apiResponse(null, $message, 401);
    }

    /**
     * Generate a forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return self::apiResponse(null, $message, 403);
    }

    /**
     * Generate a created response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function createdResponse(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::apiResponse($data, $message, 201);
    }

    /**
     * Generate a no content response
     *
     * @return JsonResponse
     */
    public static function noContentResponse(): JsonResponse
    {
        return self::apiResponse(null, '', 204);
    }
}
