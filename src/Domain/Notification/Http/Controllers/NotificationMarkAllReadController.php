<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Notification\Http\Request\NotificationMarkAllReadRequest;
use Domain\Notification\UseCases\NotificationMarkAllReadUseCase;
use Illuminate\Http\JsonResponse;

final class NotificationMarkAllReadController extends ApiController
{
    public function __invoke(NotificationMarkAllReadRequest $request): JsonResponse
    {
        NotificationMarkAllReadUseCase::make()->handle($request->user());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS);
    }
}
