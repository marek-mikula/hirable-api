<?php

declare(strict_types=1);

namespace Domain\Notification\Concerns;

use Domain\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

/**
 * @property-read Collection<Notification> $notifications
 *
 * @mixin Model
 */
trait Notifiable
{
    use BaseNotifiable;

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            related: Notification::class,
            name: 'notifiable',
        )->latest();
    }
}
