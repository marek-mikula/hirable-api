<?php

declare(strict_types=1);

namespace Support\NotificationPreview\Data;

use App\Notifications\Notification;
use Domain\Notification\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Spatie\LaravelData\Data;

class NotificationData extends Data
{
    public string $label;

    public string $description;

    public \Closure $notification;

    public \Closure $notifiable;

    public ?string $key;

    public function getNotifiable(): Model|AnonymousNotifiable
    {
        return once(fn () => call_user_func($this->notifiable));
    }

    public function getNotification(Model|AnonymousNotifiable $notifiable): Notification
    {
        return once(fn () => call_user_func($this->notification, $notifiable));
    }

    public function isAnonymous(): bool
    {
        return once(fn (): bool => $this->getNotifiable() instanceof AnonymousNotifiable);
    }

    public function getNotifiableAsJson(): string
    {
        return once(function (): string {
            $notifiable = $this->getNotifiable();

            if ($notifiable instanceof Model) {
                return $notifiable->toJson(JSON_PRETTY_PRINT);
            }

            $array = [];

            if (array_key_exists('mail', $notifiable->routes)) {
                $array['mail'] = $notifiable->routeNotificationFor('mail');
            }

            return json_encode($array, JSON_PRETTY_PRINT);
        });
    }

    /**
     * @return class-string<Model|AnonymousNotifiable>
     */
    public function getNotifiableClass(): string
    {
        return once(fn (): string => $this->getNotifiable()::class);
    }

    /**
     * @return class-string<Notification>
     */
    public function getNotificationClass(): string
    {
        return once(fn (): string => $this->getNotification($this->getNotifiable())::class);
    }

    /**
     * @return string[]
     */
    public function getChannels(): array
    {
        $notifiable = $this->getNotifiable();

        return once(fn (): array => $this->getNotification($notifiable)->via($this->getNotifiable()));
    }

    public function getType(): NotificationTypeEnum
    {
        $notifiable = $this->getNotifiable();

        return once(fn (): NotificationTypeEnum => $this->getNotification($notifiable)->type);
    }

    public function getDatabase(): array
    {
        throw_if(!$this->hasChannel('database'), new \Exception(sprintf('Notification %s does not support database channel.', $this->getNotificationClass())));

        $notifiable = $this->getNotifiable();

        return once(fn (): array => $this->getNotification($notifiable)->toDatabase($notifiable));
    }

    public function getDatabaseType(): string
    {
        $notifiable = $this->getNotifiable();

        return once(fn (): string => $this->getNotification($notifiable)->databaseType($notifiable));
    }

    public function getMail(): NotificationMailData
    {
        throw_if(!$this->hasChannel('mail'), new \Exception(sprintf('Notification %s does not support mail channel.', $this->getNotificationClass())));

        $notifiable = $this->getNotifiable();

        return once(fn (): NotificationMailData => NotificationMailData::create($this->getNotification($notifiable)->toMail($notifiable)));
    }

    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->getChannels());
    }

    public function is(NotificationData $data): bool
    {
        return $data->getType() === $this->getType() && $data->key === $this->key;
    }

    public static function create(
        string $label,
        string $description,
        \Closure $notification,
        \Closure $notifiable,
        ?string $key = null,
    ): static {
        return static::from([
            'label' => $label,
            'description' => $description,
            'notification' => $notification,
            'notifiable' => $notifiable,
            'key' => $key,
        ]);
    }
}
