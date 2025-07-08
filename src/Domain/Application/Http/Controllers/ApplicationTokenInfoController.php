<?php

declare(strict_types=1);

namespace Domain\Application\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Http\Controllers\ApiController;
use Domain\Application\Http\Requests\ApplicationTokenInfoRequest;
use Domain\Application\Http\Resources\TokenDataResource;
use Domain\Application\Services\ApplicationValidatorService;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenInfoException;
use Domain\Application\TokenProcessing\TokenDataExtractorService;
use Illuminate\Http\JsonResponse;

class ApplicationTokenInfoController extends ApiController
{
    public function __construct(
        private readonly ApplicationValidatorService $applicationValidatorService,
        private readonly TokenDataExtractorService $tokenDataExtractorService,
    ) {
    }

    public function __invoke(ApplicationTokenInfoRequest $request): JsonResponse
    {
        $token = (string) $request->query('token');

        if (empty($token)) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_MISSING);
        }

        try {
            $tokenData = $this->tokenDataExtractorService->extract($token);
        } catch (UnableExtractTokenDataException|UnableExtractTokenInfoException $e) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID);
        }

        $this->applicationValidatorService->validate($tokenData);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, new TokenDataResource($tokenData));
    }
}
