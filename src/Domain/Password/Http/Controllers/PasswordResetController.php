<?php

declare(strict_types=1);

namespace Domain\Password\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Password\Http\Requests\PasswordResetRequest;
use Domain\Password\UseCases\ResetPasswordUseCase;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends ApiController
{
    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        ResetPasswordUseCase::make()->handle($request->getToken(), $request->getNewPassword());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
