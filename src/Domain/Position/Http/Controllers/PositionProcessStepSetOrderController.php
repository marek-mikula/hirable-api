<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionProcessStepSetOrderRequest;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionProcessStepSetOrderUseCase;
use Illuminate\Http\JsonResponse;

class PositionProcessStepSetOrderController extends ApiController
{
    public function __invoke(PositionProcessStepSetOrderRequest $request, Position $position): JsonResponse
    {
        $positionProcessSteps = PositionProcessStepSetOrderUseCase::make()->handle($position, $request->getOrder());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'order' => $positionProcessSteps->pluck('order', 'id'),
        ]);
    }
}
