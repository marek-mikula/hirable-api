<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Support\Setting\Enums\SettingKeyEnum;

/**
 * @extends BaseFactory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'key' => SettingKeyEnum::GRID_USERS,
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
