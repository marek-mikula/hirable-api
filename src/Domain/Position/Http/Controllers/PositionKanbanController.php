<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionKanbanRequest;
use Domain\Position\Http\Resources\Collections\KanbanStepCollection;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Http\JsonResponse;

class PositionKanbanController extends ApiController
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function __invoke(PositionKanbanRequest $request, Position $position): JsonResponse
    {
        $positionProcessSteps = $this->positionProcessStepRepository->getStepsForKanban($position);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'kanbanSteps' => new KanbanStepCollection($positionProcessSteps),
        ]);
    }
}
