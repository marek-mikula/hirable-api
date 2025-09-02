<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionCandidateActionShowRequest;
use Domain\Position\Http\Request\PositionCandidateActionStoreRequest;
use Domain\Position\Http\Resources\PositionCandidateActionResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\UseCases\PositionCandidateActionStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionCandidateActionController extends ApiController
{
    public function store(PositionCandidateActionStoreRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidateAction = PositionCandidateActionStoreUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            data: $request->toData()
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateAction' => new PositionCandidateActionResource($positionCandidateAction),
        ]);
    }

    public function show(PositionCandidateActionShowRequest $request, Position $position, PositionCandidate $positionCandidate, PositionCandidateAction $positionCandidateAction): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateAction' => new PositionCandidateActionResource($positionCandidateAction),
        ]);
    }
}
