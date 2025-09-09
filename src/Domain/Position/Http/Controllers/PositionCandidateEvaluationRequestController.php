<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionCandidateEvaluationRequestRequest;
use Domain\Position\Http\Resources\PositionCandidateEvaluationResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\UseCases\PositionCandidateEvaluationRequestUseCase;
use Illuminate\Http\JsonResponse;

class PositionCandidateEvaluationRequestController extends ApiController
{
    public function __invoke(PositionCandidateEvaluationRequestRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidateEvaluations = PositionCandidateEvaluationRequestUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            data: $request->toData()
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateEvaluations' => new ResourceCollection(PositionCandidateEvaluationResource::class, $positionCandidateEvaluations),
        ]);
    }
}
