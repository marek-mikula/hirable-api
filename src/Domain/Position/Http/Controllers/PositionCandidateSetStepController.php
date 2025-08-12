<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionCandidateSetStepRequest;
use Domain\Position\Http\Resources\PositionCandidateResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\Position\UseCases\PositionCandidateSetStepUseCase;
use Illuminate\Http\JsonResponse;

class PositionCandidateSetStepController extends ApiController
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function __invoke(PositionCandidateSetStepRequest $request, Position $position, PositionCandidate $positionCandidate): JsonResponse
    {
        $positionProcessStep = $this->positionProcessStepRepository->find($request->getPositionProcessStepId());

        abort_if(empty($positionProcessStep), code: 400);

        $positionCandidate = PositionCandidateSetStepUseCase::make()->handle($position, $positionCandidate, $positionProcessStep);

        $positionCandidate->loadMissing('candidate');

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionCandidate' => new PositionCandidateResource($positionCandidate),
        ]);
    }
}
