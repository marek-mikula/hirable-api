<?php

declare(strict_types=1);

namespace Domain\Verification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Verification\Http\Requests\VerificationVerifyEmailRequest;
use Domain\Verification\UseCases\VerifyEmailUseCase;
use Illuminate\Http\JsonResponse;

class VerificationController extends ApiController
{
    public function verifyEmail(VerificationVerifyEmailRequest $request): JsonResponse
    {
        VerifyEmailUseCase::make()->handle($request->getToken());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
