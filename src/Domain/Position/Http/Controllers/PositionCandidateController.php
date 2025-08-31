<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionCandidateShowRequest;
use Domain\Position\Http\Resources\PositionCandidateResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Illuminate\Http\JsonResponse;

class PositionCandidateController extends ApiController
{
    public function show(PositionCandidateShowRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionCandidate->loadMissing([
            'candidate',
            'candidate.files',
            'actions',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidate' => new PositionCandidateResource($positionCandidate),
        ]);
    }
}
