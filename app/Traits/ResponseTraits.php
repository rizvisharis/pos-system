<?php

namespace App\Traits;

use App\Utils\Constants;
use Exception;
use Illuminate\Http\ResponseTrait as ParentResponseTraits;
use Illuminate\Validation\ValidationException;

trait ResponseTraits
{
    use ParentResponseTraits;

    public function successResponse($data, $message, $code = null)
    {
        return response()->json(
            [
                'status' => true,
                'message' => $message,
                'data' => $data,
            ],
            $code ?? Constants::$ERROR_CODE['success']
        );
    }

    public function validationErrorResponse(ValidationException $exception)
    {
        $errorMessage = collect($exception->errors())->map(function ($message) {
            return implode(' ', $message);
        })->values()->implode(' ');

        return response()->json(
            [
                'status' => false,
                'code' => Constants::$ERROR_CODE['unprocessable_entity'],
                'message' => $errorMessage,
            ],
            Constants::$ERROR_CODE['unprocessable_entity']
        );
    }

    public function exceptionErrorResponse(Exception $exception)
    {
        $code = (int) $exception->getCode();

        return response()->json(
            [
                'status' => false,
                'code' => $code,
                'message' => $exception->getMessage(),
            ],
            collect(Constants::$ERROR_CODE)->contains($code) ? $code : Constants::$ERROR_CODE['internal_server_error']
        );
    }

    public function errorResponse($message, $errorCode)
    {
        return response()->json(
            [
                'status' => false,
                'code' => $errorCode,
                'message' => $message,
            ],
            $errorCode
        );
    }
}
