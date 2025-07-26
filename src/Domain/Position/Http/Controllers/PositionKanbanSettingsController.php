<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionKanbanSettingsRequest;
use Domain\Position\Http\Resources\Collections\KanbanStepCollection;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionUpdateKanbanSettingsUseCase;
use Illuminate\Http\JsonResponse;

class PositionKanbanSettingsController extends ApiController
{
    public function __invoke(PositionKanbanSettingsRequest $request, Position $position): JsonResponse
    {
        $positionProcessSteps = PositionUpdateKanbanSettingsUseCase::make()->handle($position, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'kanbanSteps' => new KanbanStepCollection($positionProcessSteps),
        ]);
    }
}
