<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionCandidateShareDeleteRequest;
use Domain\Position\Http\Request\PositionCandidateShareIndexRequest;
use Domain\Position\Http\Request\PositionCandidateShareStoreRequest;
use Domain\Position\Http\Resources\PositionCandidateShareResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\Position\UseCases\PositionCandidateShareDeleteUseCase;
use Domain\Position\UseCases\PositionCandidateShareStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionCandidateShareController extends ApiController
{
    public function index(PositionCandidateShareIndexRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        /** @var PositionCandidateShareRepositoryInterface $repository */
        $repository = app(PositionCandidateShareRepositoryInterface::class);

        $positionCandidateShares = $repository->index($positionCandidate, [
            'user',
            'creator',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateShares' => new ResourceCollection(PositionCandidateShareResource::class, $positionCandidateShares),
        ]);
    }

    public function store(PositionCandidateShareStoreRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidateShares = PositionCandidateShareStoreUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            hiringManagers: $request->getHiringManagers()
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidateShares' => new ResourceCollection(PositionCandidateShareResource::class, $positionCandidateShares),
        ]);
    }

    public function delete(PositionCandidateShareDeleteRequest $request, Position $position, PositionCandidate $positionCandidate, PositionCandidateShare $positionCandidateShare): JsonResponse
    {
        PositionCandidateShareDeleteUseCase::make()->handle(
            user: $request->user(),
            position: $position,
            positionCandidate: $positionCandidate,
            positionCandidateShare: $positionCandidateShare
        );

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
