<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionApprovalDecideRequest;
use Domain\Position\Http\Resources\PositionApprovalResource;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\UseCases\PositionApprovalDecideUseCase;
use Illuminate\Http\JsonResponse;

class PositionApprovalDecideController extends ApiController
{
    public function __invoke(PositionApprovalDecideRequest $request, Position $position, PositionApproval $positionApproval): JsonResponse
    {
        $positionApproval = PositionApprovalDecideUseCase::make()->handle($request->user(), $position, $positionApproval, $request->toData());

        $positionApproval->loadMissing([
            'modelHasPosition',
            'modelHasPosition.model',
        ]);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'approval' => new PositionApprovalResource($positionApproval),
        ]);
    }
}
