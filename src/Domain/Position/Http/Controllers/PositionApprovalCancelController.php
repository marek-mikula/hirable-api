<?php

declare(strict_types=1);

namespace Domain\Position\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Position\Http\Request\PositionApprovalCancelRequest;
use Domain\Position\Models\Position;
use Domain\Position\UseCases\PositionApprovalCancelUseCase;
use Illuminate\Http\JsonResponse;

class PositionApprovalCancelController extends ApiController
{
    public function __invoke(PositionApprovalCancelRequest $request, Position $position): JsonResponse
    {
        PositionApprovalCancelUseCase::make()->handle($request->user(), $position);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
