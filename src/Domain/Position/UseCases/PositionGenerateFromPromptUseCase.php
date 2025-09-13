<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\PositionTransformer;
use Domain\AI\Services\AIService;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\User\Models\User;

class PositionGenerateFromPromptUseCase extends UseCase
{
    public function __construct(
        private readonly PositionTransformer $positionTransformer,
        private readonly AIService $AIService
    ) {
    }

    public function handle(User $user, string $prompt): array
    {
        $attributes = $this->AIService->generatePositionFromPrompt($user, $prompt);

        return collect($attributes)
            ->filter(fn (mixed $value, string $key): bool =>
                // filter invalid keys
                PositionFieldEnum::tryFrom($key) !== null)
            ->map(fn (mixed $value, string $key): mixed => $this->positionTransformer->transformField($key, $value))
            ->all();
    }
}
