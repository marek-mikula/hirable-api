<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionSuggestRequest;
use Domain\Position\Repositories\PositionSuggestRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PositionSuggestController extends ApiController
{
    public function __construct(
        private readonly PositionSuggestRepositoryInterface $positionSuggestRepository,
    ) {
    }

    public function suggestDepartments(PositionSuggestRequest $request): JsonResponse
    {
        $values = $this->positionSuggestRepository->suggestDepartments($request->user(), $request->getQuery());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'values' => $values
        ]);
    }

    public function suggestTechnologies(PositionSuggestRequest $request): JsonResponse
    {
        $values = $this->positionSuggestRepository->suggestTechnologies($request->user(), $request->getQuery());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'values' => $values
        ]);
    }

    public function suggestCertificates(PositionSuggestRequest $request): JsonResponse
    {
        $values = $this->positionSuggestRepository->suggestCertificates($request->user(), $request->getQuery());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'values' => $values
        ]);
    }
}
