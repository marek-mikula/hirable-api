<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;

class HttpException extends BaseHttpException
{
    public function __construct(
        private readonly ResponseCodeEnum $responseCode,
        private readonly array $data = [],
        array $headers = [],
        string $message = '',
    ) {
        parent::__construct(
            statusCode: $this->responseCode->getStatusCode(),
            message: $message,
            headers: $headers,
        );
    }

    public function getResponseCode(): ResponseCodeEnum
    {
        return $this->responseCode;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
