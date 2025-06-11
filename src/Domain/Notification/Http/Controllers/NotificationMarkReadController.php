<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Notification\Http\Request\NotificationMarkReadRequest;
use Domain\Notification\Http\Resources\NotificationResource;
use Domain\Notification\Models\Notification;
use Domain\Notification\UseCases\NotificationMarkReadUseCase;
use Illuminate\Http\JsonResponse;

class NotificationMarkReadController extends ApiController
{
    public function __invoke(NotificationMarkReadRequest $request, Notification $notification): JsonResponse
    {
        $notification = NotificationMarkReadUseCase::make()->handle($notification);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'notification' => new NotificationResource($notification),
        ]);
    }
}
