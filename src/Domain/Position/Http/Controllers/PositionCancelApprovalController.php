<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionCancelApprovalRequest;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\CancelApprovalUseCase;
use Illuminate\Http\JsonResponse;

class PositionCancelApprovalController extends ApiController
{
    public function __invoke(PositionCancelApprovalRequest $request, Position $position): JsonResponse
    {
        CancelApprovalUseCase::make()->handle($request->user(), $position);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
