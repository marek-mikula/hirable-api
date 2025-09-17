<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionCandidateSetPriorityRequest;
use Domain\Position\Http\Resources\PositionCandidateResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\UseCases\PositionCandidateSetPriorityUseCase;
use Illuminate\Http\JsonResponse;

final class PositionCandidateSetPriorityController extends ApiController
{
    public function __invoke(PositionCandidateSetPriorityRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidate = PositionCandidateSetPriorityUseCase::make()->handle($positionCandidate, $request->getPriority());

        $positionCandidate
            ->loadMissing(['actions', 'candidate', 'step', 'evaluations'])
            ->loadCount(['shares']);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidate' => new PositionCandidateResource($positionCandidate),
        ]);
    }
}
