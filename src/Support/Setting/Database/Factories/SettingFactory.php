<?php

declare(strict_types=1);

namespace Support\Setting\Database\Factories;

use Database\Factories\Factory;
use Domain\User\Models\User;
use Support\Setting\Enums\SettingKeyEnum;
use Support\Setting\Models\Setting;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->isMaking ? null : User::factory(),
            'key' => SettingKeyEnum::GRID_CANDIDATE,
            'data' => [],
        ];
    }

    public function ofUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function ofKey(SettingKeyEnum $key): static
    {
        return $this->state(fn (array $attributes) => [
            'key' => $key,
        ]);
    }

    public function ofData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => $data,
        ]);
    }
}
