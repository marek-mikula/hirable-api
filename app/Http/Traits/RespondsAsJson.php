<?php

namespace App\Http\Traits;

use App\Enums\ResponseCodeEnum;
use Illuminate\Http\JsonResponse;

trait RespondsAsJson
{
    protected function jsonResponse(
        ResponseCodeEnum $code,
        array $data = [],
        array $headers = [],
        string $message = ''
    ): JsonResponse {
        $content = ['code' => $code->name];

        if (!empty($message)) {
            $content['message'] = $message;
        }

        if (!empty($data)) {
            $content['data'] = $data;
        }

        return response()->json(data: $content, status: $code->getStatusCode(), headers: $headers);
    }
}
