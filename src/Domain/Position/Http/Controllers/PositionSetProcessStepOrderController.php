<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionSetProcessStepOrderRequest;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionSetProcessStepOrderUseCase;
use Illuminate\Http\JsonResponse;

class PositionSetProcessStepOrderController extends ApiController
{
    public function __invoke(PositionSetProcessStepOrderRequest $request, Position $position): JsonResponse
    {
        PositionSetProcessStepOrderUseCase::make()->handle($position, $request->getOrder());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
