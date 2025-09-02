<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\ResourceCollection;
use Domain\Position\Http\Request\PositionProcessStepDeleteRequest;
use Domain\Position\Http\Request\PositionProcessStepIndexRequest;
use Domain\Position\Http\Request\PositionProcessStepUpdateRequest;
use Domain\Position\Http\Request\PositionProcessStepStoreRequest;
use Domain\Position\Http\Resources\PositionProcessStepResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\UseCases\PositionProcessStepDeleteUseCase;
use Domain\Position\UseCases\PositionProcessStepUpdateUseCase;
use Domain\Position\UseCases\PositionProcessStepStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionProcessStepController extends ApiController
{
    public function index(PositionProcessStepIndexRequest $request, Position $position): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionProcessSteps' => new ResourceCollection(PositionProcessStepResource::class, $position->steps),
        ]);
    }

    public function store(PositionProcessStepStoreRequest $request, Position $position): JsonResponse
    {
        $positionProcessStep = PositionProcessStepStoreUseCase::make()->handle($position, $request->getProcessStep());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionProcessStep' => new PositionProcessStepResource($positionProcessStep),
        ]);
    }

    public function update(PositionProcessStepUpdateRequest $request, Position $position, PositionProcessStep $positionProcessStep): JsonResponse
    {
        $positionProcessStep = PositionProcessStepUpdateUseCase::make()->handle($position, $positionProcessStep, $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'positionProcessStep' => new PositionProcessStepResource($positionProcessStep)
        ]);
    }

    public function delete(PositionProcessStepDeleteRequest $request, Position $position, PositionProcessStep $positionProcessStep): JsonResponse
    {
        PositionProcessStepDeleteUseCase::make()->handle($position, $positionProcessStep);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
