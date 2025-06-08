<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionDuplicateRequest;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionDuplicateUseCase;
use Illuminate\Http\JsonResponse;

class PositionDuplicateController extends ApiController
{
    public function __invoke(PositionDuplicateRequest $request, Position $position): JsonResponse
    {
        $position = PositionDuplicateUseCase::make()->handle($request->user(), $position);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'id' => $position->id,
        ]);
    }
}
