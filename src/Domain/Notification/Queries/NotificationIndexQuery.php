<?php

declare(strict_types=1);

namespace Domain\Notification\Queries;

use App\Queries\Query;
use Domain\Notification\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class NotificationIndexQuery extends Query
{
    public function handle(Model $model, int $page): LengthAwarePaginator
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
