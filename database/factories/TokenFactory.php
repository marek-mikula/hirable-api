<?php

namespace Database\Factories;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Str;
use Support\Token\Enums\TokenTypeEnum;

/**
 * @extends BaseFactory<Token>
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
        return $this->state(fn (array $attributes) => [
            'used_at' => $datetime ?? now()->subDay(),
        ]);
    }

    public function expired(?Carbon $datetime = null): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => $datetime ?? now()->subDay(),
        ]);
    }

    public function ofMergedData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => array_merge($attributes['data'] ?? [], $data),
        ]);
    }

    public function ofData(array $data): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => $data,
        ]);
    }

    public function ofUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function ofType(TokenTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function ofToken(string $token): static
    {
        return $this->state(fn (array $attributes) => [
            'token' => $token,
        ]);
    }
}
