<?php

declare(strict_types=1);

namespace Domain\Notification\UseCases;

use App\UseCases\UseCase;
use Domain\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NotificationMarkAllReadUseCase extends UseCase
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function handle(Model $model): void
    {
        $notifications = $this->notificationRepository->getUnreadForModel($model);

        if ($notifications->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($notifications): void {
            foreach ($notifications as $notification) {
                $this->notificationRepository->markRead($notification);
            }
        }, attempts: 5);
    }
}
