<?php

declare(strict_types=1);

namespace Domain\Notification\Database\Factories;

use Database\Factories\Factory;
use Domain\Notification\Enums\NotificationTypeEnum;
use Domain\Notification\Models\Notification;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $notifiable = fake()->randomElement([
            User::class,
        ]);

        /** @var NotificationTypeEnum $type */
        $type = fake()->randomElement(NotificationTypeEnum::cases());

        return [
            'type' => $type,
            'notifiable_type' => $notifiable,
            'notifiable_id' => $this->isMaking ? null : $notifiable::factory(),
            'data' => [],
        ];
    }

    public function ofType(NotificationTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function ofData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => $data,
        ]);
    }

    public function ofMergedData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => array_merge($attributes['data'] ?? [], $data),
        ]);
    }
}
