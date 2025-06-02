<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionApprovalUpdateRequest;
use Domain\Position\Http\Resources\PositionApprovalResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\UseCases\UpdatePositionApprovalUseCase;
use Illuminate\Http\JsonResponse;

class PositionApprovalController extends ApiController
{
    public function update(PositionApprovalUpdateRequest $request, Position $position, PositionApproval $approval): JsonResponse
    {
        $approval = UpdatePositionApprovalUseCase::make()->handle($request->user(), $position, $approval, $request->toData());

        $approval->loadMissing([
            'modelHasPosition',
            'modelHasPosition.model',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'approval' => new PositionApprovalResource($approval),
        ]);
    }
}
