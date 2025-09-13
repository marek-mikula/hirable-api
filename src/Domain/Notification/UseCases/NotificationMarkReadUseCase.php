<?php

declare(strict_types=1);

namespace Domain\Notification\UseCases;

use App\UseCases\UseCase;
use Domain\Notification\Models\Notification;
use Domain\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class NotificationMarkReadUseCase extends UseCase
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function handle(Notification $notification): Notification
    {
        if ($notification->read_at) {
            return $notification;
        }

        return DB::transaction(fn (): Notification => $this->notificationRepository->markRead($notification), attempts: 5);
    }
}
