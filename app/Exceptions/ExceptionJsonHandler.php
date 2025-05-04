<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ResponseCodeEnum;
use App\Http\Traits\RespondsAsJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ExceptionJsonHandler
{
    use RespondsAsJson;

    public function handle(Throwable $e, Request $request): JsonResponse
    {
        $isDebug = app()->hasDebugModeEnabled();

        if ($e instanceof TokenMismatchException) {
            return $this->jsonResponse(code: ResponseCodeEnum::TOKEN_MISMATCH);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->jsonResponse(code: ResponseCodeEnum::METHOD_NOT_ALLOWED, headers: $e->getHeaders());
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->jsonResponse(code: ResponseCodeEnum::NOT_FOUND, headers: $e->getHeaders());
        }

        if ($e instanceof ModelNotFoundException) {
            $data = $isDebug ? ['model' => $e->getModel(), 'ids' => $e->getIds()] : [];

            return $this->jsonResponse(code: ResponseCodeEnum::NOT_FOUND, data: $data);
        }

        if ($e instanceof AuthenticationException) {
            return $this->jsonResponse(code: ResponseCodeEnum::UNAUTHENTICATED);
        }

        if ($e instanceof AuthorizationException) {
            return $this->jsonResponse(code: ResponseCodeEnum::UNAUTHORIZED);
        }

        if ($e instanceof ValidationException) {
            return $this->jsonResponse(code: ResponseCodeEnum::INVALID_CONTENT, data: ['errors' => $e->errors()]);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return $this->jsonResponse(code: ResponseCodeEnum::TOO_MANY_ATTEMPTS, headers: $e->getHeaders());
        }

        // own common http exception
        if ($e instanceof HttpException) {
            return $this->jsonResponse(code: $e->getResponseCode(), data: $e->getData(), headers: $e->getHeaders(), message: $e->getMessage());
        }

        $message = 'Oops. Something went wrong.';
        $data = [];

        if ($isDebug) {
            $message = $e->getMessage();
            $data['trace'] = collect($e->getTrace())
                ->map(static fn (array $trace): string => sprintf(
                    '%s:%s (@%s)',
                    $trace['file'] ?? $trace['class'] ?? '',
                    $trace['line'] ?? '',
                    $trace['function'],
                ))
                ->all();
        }

        // Laravel base http exception from Symphony
        if ($e instanceof BaseHttpException) {
            return $this->jsonResponse(code: ResponseCodeEnum::SERVER_ERROR, data: $data, headers: $e->getHeaders(), message: $message);
        }

        // common server error
        return $this->jsonResponse(code: ResponseCodeEnum::SERVER_ERROR, data: $data, message: $message);
    }
}
