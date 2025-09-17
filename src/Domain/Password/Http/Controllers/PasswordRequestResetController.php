<?php

declare(strict_types=1);

namespace Domain\Password\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Password\Http\Requests\PasswordRequestResetRequest;
use Domain\Password\UseCases\RequestPasswordResetUseCase;
use Illuminate\Http\JsonResponse;

final class PasswordRequestResetController extends ApiController
{
    public function __invoke(PasswordRequestResetRequest $request): JsonResponse
    {
        RequestPasswordResetUseCase::make()->handle(email: $request->getEmail());

        // send success every time even though the user
        // does not exist, so the user cannot farm email
        // addresses in the system
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
