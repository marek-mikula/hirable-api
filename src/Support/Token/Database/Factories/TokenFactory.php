<?php

declare(strict_types=1);

namespace Support\Token\Database\Factories;

use Carbon\Carbon;
use Database\Factories\Factory;
use Domain\User\Models\User;
use Illuminate\Support\Str;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

/**
 * @extends Factory<Token>
 */
class TokenFactory extends Factory
{
    protected $model = Token::class;

    public function definition(): array
    {
        /** @var TokenTypeEnum $type */
        $type = fake()->randomElement(TokenTypeEnum::cases());

        return [
            'user_id' => null,
            'type' => $type,
            'token' => Str::random(40),
            'data' => [],
            'used_at' => null,
            'valid_until' => now()->addMinutes(30),
        ];
    }

    public function used(?Carbon $datetime = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'used_at' => $datetime ?? now()->subDay(),
        ]);
    }

    public function unused(): static
    {
        return $this->state(fn (array $attributes): array => [
            'used_at' => null,
        ]);
    }

    public function expired(?Carbon $datetime = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'valid_until' => $datetime ?? now()->subDay(),
        ]);
    }

    public function ofMergedData(array $data): static
    {
        return $this->state(fn (array $attributes): array => [
            'data' => array_merge($attributes['data'] ?? [], $data),
        ]);
    }

    public function ofData(array $data): static
    {
        return $this->state(fn (array $attributes): array => [
            'data' => $data,
        ]);
    }

    public function ofUser(User $user): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $user->id,
        ]);
    }

    public function ofType(TokenTypeEnum $type): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => $type,
        ]);
    }

    public function ofToken(string $token): static
    {
        return $this->state(fn (array $attributes): array => [
            'token' => $token,
        ]);
    }
}
