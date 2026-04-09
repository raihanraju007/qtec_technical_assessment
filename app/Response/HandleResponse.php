<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class HandleResponse
{
    public static function success($data = [], string $message = '', int $code = Response::HTTP_OK): JsonResponse|JsonResource
    {
        if ($data instanceof JsonResource) {
            return $data;
        }

        $response = [];

        if ($message !== '') {
            $response['message'] = $message;
        }

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public static function error(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
