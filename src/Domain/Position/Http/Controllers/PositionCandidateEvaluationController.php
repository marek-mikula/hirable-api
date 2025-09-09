<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionCandidateEvaluationDeleteRequest;
use Domain\Position\Http\Request\PositionCandidateEvaluationIndexRequest;
use Domain\Position\Http\Request\PositionCandidateEvaluationStoreRequest;
use Domain\Position\Http\Resources\PositionCandidateEvaluationResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Domain\Position\UseCases\PositionCandidateEvaluationDeleteUseCase;
use Domain\Position\UseCases\PositionCandidateEvaluationStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionCandidateEvaluationController extends ApiController
{
    public function index(PositionCandidateEvaluationIndexRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        /** @var PositionCandidateEvaluationRepositoryInterface $repository */
        $repository = app(PositionCandidateEvaluationRepositoryInterface::class);

        $positionCandidateEvaluations = $repository->index($positionCandidate, [
            'user',
            'creator',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateEvaluations' => new ResourceCollection(PositionCandidateEvaluationResource::class, $positionCandidateEvaluations),
        ]);
    }

    public function store(PositionCandidateEvaluationStoreRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidateEvaluation = PositionCandidateEvaluationStoreUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            data: $request->toData()
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateEvaluation' => new PositionCandidateEvaluationResource($positionCandidateEvaluation)
        ]);
    }

    public function delete(PositionCandidateEvaluationDeleteRequest $request, Position $position, PositionCandidate $positionCandidate, PositionCandidateEvaluation $positionCandidateEvaluation): JsonResponse
    {
        PositionCandidateEvaluationDeleteUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            positionCandidateEvaluation: $positionCandidateEvaluation
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
