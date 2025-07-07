<?php

declare(strict_types=1);

namespace Domain\Notification\UseCases;

use App\UseCases\UseCase;
use Domain\Notification\Models\Notification;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

class NotificationIndexUseCase extends UseCase
{
    public function handle(Model $model, int $page): Paginator
    {
        return Notification::query()
            ->whereMorphedTo('notifiable', $model)
            ->orderBy('created_at', 'desc')
            ->paginate(
                perPage: 20,
                page: $page,
            );
    }
}
