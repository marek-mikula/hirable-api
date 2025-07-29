<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionProcessStepStoreRequest;
use Domain\Position\Http\Resources\PositionProcessStepResource;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionProcessStepStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionProcessStepController extends ApiController
{
    public function store(PositionProcessStepStoreRequest $request, Position $position): JsonResponse
    {
        $positionProcessStep = PositionProcessStepStoreUseCase::make()->handle($position, $request->getProcessStep());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionProcessStep' => new PositionProcessStepResource($positionProcessStep),
        ]);
    }
}
