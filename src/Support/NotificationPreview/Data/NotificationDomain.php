<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class NotificationDomain extends Data
{
    public string $key;

    /**
     * @var Collection<NotificationData>
     */
    public Collection $notifications;

    public function getLabel(): string
    {
        return once(fn (): string => str($this->key)->replace('-', ' ')->title());
    }

    public static function create(string $key, array $notifications): static
    {
        return static::from(['key' => $key, 'notifications' => collect($notifications)]);
    }
}
