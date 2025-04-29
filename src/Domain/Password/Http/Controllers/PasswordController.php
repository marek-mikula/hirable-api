<?php

declare(strict_types=1);

namespace Domain\Password\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Password\Http\Requests\PasswordRequestResetRequest;
use Domain\Password\Http\Requests\PasswordResetRequest;
use Domain\Password\UseCases\RequestPasswordResetUseCase;
use Domain\Password\UseCases\ResetPasswordUseCase;
use Illuminate\Http\JsonResponse;

class PasswordController extends ApiController
{
    public function requestReset(PasswordRequestResetRequest $request): JsonResponse
    {
        RequestPasswordResetUseCase::make()->handle(email: $request->getEmail());

        // send success every time even though the user
        // does not exist, so the user cannot farm email
        // addresses in the system
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }

    public function reset(PasswordResetRequest $request): JsonResponse
    {
        ResetPasswordUseCase::make()->handle($request->getToken(), $request->getNewPassword());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
