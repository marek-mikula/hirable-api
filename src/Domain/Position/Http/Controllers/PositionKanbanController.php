<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionKanbanRequest;
use Domain\Position\Http\Resources\KanbanStepResource;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class PositionKanbanController extends ApiController
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function __invoke(PositionKanbanRequest $request, Position $position): JsonResponse
    {
        $positionProcessSteps = $this->positionProcessStepRepository->getByPosition($position, [
            'positionCandidates' => function (Builder $query) {
                $query->with(['candidate', 'latestAction'])->withCount('actions');
            },
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'kanbanSteps' => new ResourceCollection(KanbanStepResource::class, $positionProcessSteps),
        ]);
    }
}
