<?php

declare(strict_types=1);

namespace Domain\Application\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Http\Controllers\ApiController;
use Domain\Application\Http\Requests\ApplicationApplyRequest;
use Domain\Application\Http\Resources\ApplicationResource;
use Domain\Application\Services\ApplicationValidatorService;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenInfoException;
use Domain\Application\TokenProcessing\TokenPackageExtractorService;
use Domain\Application\UseCases\ApplicationApplyUseCase;
use Illuminate\Http\JsonResponse;

class ApplicationApplyController extends ApiController
{
    public function __construct(
        private readonly TokenPackageExtractorService $tokenPackageExtractorService,
        private readonly ApplicationValidatorService $applicationValidatorService,
    ) {
    }

    public function __invoke(ApplicationApplyRequest $request): JsonResponse
    {
        $token = (string) $request->query('token');

        if (empty($token)) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_MISSING);
        }

        try {
            $tokenPackage = $this->tokenPackageExtractorService->extract($token);
        } catch (UnableExtractTokenDataException|UnableExtractTokenInfoException) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID);
        }

        $this->applicationValidatorService->validate($tokenPackage->tokenData);

        $application = ApplicationApplyUseCase::make()->handle($tokenPackage, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
           'application' => new ApplicationResource($application),
        ]);
    }
}
