<?php

declare(strict_types=1);

namespace Domain\Notification\UseCases;

use App\UseCases\UseCase;
use Domain\Notification\Models\Notification;
use Domain\User\Models\User;

class NotificationUnreadUseCase extends UseCase
{
    public function handle(User $user): int
    {
        return Notification::query()
            ->whereMorphedTo('notifiable', $user)
            ->unread()
            ->count();
    }
}
