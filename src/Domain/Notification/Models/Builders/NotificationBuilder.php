<?php

declare(strict_types=1);

namespace Domain\Notification\Models\Builders;

use App\Models\Builders\Builder;

class NotificationBuilder extends Builder
{
    public function unread(): static
    {
        return $this->whereNull('read_at');
    }
}
