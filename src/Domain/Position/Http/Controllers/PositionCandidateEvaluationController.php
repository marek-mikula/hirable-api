<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionCandidateEvaluationDeleteRequest;
use Domain\Position\Http\Request\PositionCandidateEvaluationIndexRequest;
use Domain\Position\Http\Request\PositionCandidateEvaluationStoreRequest;
use Domain\Position\Http\Request\PositionCandidateEvaluationUpdateRequest;
use Domain\Position\Http\Resources\PositionCandidateEvaluationShowResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Domain\Position\UseCases\PositionCandidateEvaluationDeleteUseCase;
use Domain\Position\UseCases\PositionCandidateEvaluationStoreUseCase;
use Domain\Position\UseCases\PositionCandidateEvaluationUpdateUseCase;
use Illuminate\Http\JsonResponse;

final class PositionCandidateEvaluationController extends ApiController
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
            'positionCandidateEvaluations' => new ResourceCollection(PositionCandidateEvaluationShowResource::class, $positionCandidateEvaluations),
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

        $positionCandidateEvaluation->loadMissing([
            'creator',
            'user',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateEvaluation' => new PositionCandidateEvaluationShowResource($positionCandidateEvaluation)
        ]);
    }

    public function update(PositionCandidateEvaluationUpdateRequest $request, Position $position, PositionCandidate $positionCandidate, PositionCandidateEvaluation $positionCandidateEvaluation): JsonResponse
    {
        $positionCandidateEvaluation = PositionCandidateEvaluationUpdateUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            positionCandidateEvaluation: $positionCandidateEvaluation,
            data: $request->toData()
        );

        $positionCandidateEvaluation->loadMissing([
            'creator',
            'user',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateEvaluation' => new PositionCandidateEvaluationShowResource($positionCandidateEvaluation)
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
