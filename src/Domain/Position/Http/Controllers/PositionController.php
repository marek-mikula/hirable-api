<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionStoreRequest;
use Domain\Position\Http\Resources\PositionResource;
use Domain\Position\UseCases\PositionStoreUseCase;
use Illuminate\Http\JsonResponse;

class PositionController extends ApiController
{
    public function store(PositionStoreRequest $request): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => $request->toData()->toArray(),
        ]);

        $position = PositionStoreUseCase::make()->handle($request->user(), $request->toData());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'position' => new PositionResource($position),
        ]);
    }
}
