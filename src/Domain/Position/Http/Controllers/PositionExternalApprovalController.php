<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionExternalApprovalDecideRequest;
use Domain\Position\Http\Request\PositionExternalApprovalShowRequest;
use Domain\Position\Http\Resources\PositionExternalResource;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\UseCases\PositionApprovalDecideUseCase;
use Illuminate\Http\JsonResponse;

class PositionExternalApprovalController extends ApiController
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
    ) {
    }

    public function show(PositionExternalApprovalShowRequest $request): JsonResponse
    {
        $approval = $this->positionApprovalRepository->findByToken($request->getToken(), with: ['position']);

        abort_if(!$approval, code: 404);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionExternalResource($approval->position),
        ]);
    }

    public function decide(PositionExternalApprovalDecideRequest $request): JsonResponse
    {
        $approval = $this->positionApprovalRepository->findByToken($request->getToken(), with: [
            'token',
            'position',
            'modelHasPosition',
            'modelHasPosition.model',
        ]);

        abort_if(!$approval, code: 404);

        PositionApprovalDecideUseCase::make()->handle($approval->modelHasPosition->model, $approval->position, $approval, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
