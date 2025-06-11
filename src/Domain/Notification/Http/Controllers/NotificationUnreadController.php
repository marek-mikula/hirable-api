<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Notification\Http\Request\NotificationUnreadRequest;
use Domain\Notification\UseCases\NotificationUnreadUseCase;
use Illuminate\Http\JsonResponse;

class NotificationUnreadController extends ApiController
{
    public function __invoke(NotificationUnreadRequest $request): JsonResponse
    {
        $count = NotificationUnreadUseCase::make()->handle($request->user());

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'count' => $count,
        ]);
    }
}
