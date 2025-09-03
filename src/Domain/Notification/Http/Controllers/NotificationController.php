<?php

declare(strict_types=1);

namespace Domain\Notification\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Collections\PaginatedResourceCollection;
use Domain\Notification\Http\Request\NotificationIndexRequest;
use Domain\Notification\Http\Resources\NotificationResource;
use Domain\Notification\UseCases\NotificationIndexUseCase;
use Illuminate\Http\JsonResponse;

class NotificationController extends ApiController
{
    public function index(NotificationIndexRequest $request): JsonResponse
    {
        $page = max((int) $request->input('page', 1), 1);

        $notifications = NotificationIndexUseCase::make()->handle($request->user(), $page);

        return $this->jsonResponse(ResponseCodeEnum::SUCCESS, [
            'notifications' => new PaginatedResourceCollection(NotificationResource::class, $notifications),
        ]);
    }
}
