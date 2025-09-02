<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionCandidateIndexRequest;
use Domain\Position\Http\Request\PositionCandidateShowRequest;
use Domain\Position\Http\Resources\PositionCandidateResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PositionCandidateController extends ApiController
{
    public function index(PositionCandidateIndexRequest $request, Position $position): JsonResponse
    {
        /** @var PositionCandidateRepositoryInterface $repository */
        $repository = app(PositionCandidateRepositoryInterface::class);

        $positionCandidates = $repository->index($position, [
            'candidate',
            'step',
            'actions'
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidates' => new ResourceCollection(PositionCandidateResource::class, $positionCandidates),
        ]);
    }

    public function show(PositionCandidateShowRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidate->loadMissing([
            'step',
            'candidate',
            'actions',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidate' => new PositionCandidateResource($positionCandidate),
        ]);
    }
}
