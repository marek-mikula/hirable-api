<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Notification\Http\Request\NotificationUnreadRequest;
use Domain\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class NotificationUnreadController extends ApiController
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function __invoke(NotificationUnreadRequest $request): JsonResponse
    {
        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'count' => $this->notificationRepository->countUnreadForModel($request->user()),
        ]);
    }
}
