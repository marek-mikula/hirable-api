<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionProcessStepStoreRequest;
use Domain\Position\Http\Resources\PositionProcessStepResource;
use Domain\Position\UseCases\PositionProcessStepStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionProcessStepController extends ApiController
{
    public function store(PositionProcessStepStoreRequest $request): JsonResponse
    {
        $step = PositionProcessStepStoreUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'step' => new PositionProcessStepResource($step),
        ]);
    }
}
