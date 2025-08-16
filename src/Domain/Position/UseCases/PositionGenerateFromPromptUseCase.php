<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\PositionTransformer;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

class PositionGenerateFromPromptUseCase extends UseCase
{
    public function __construct(
        private readonly PositionTransformer $positionTransformer,
        private readonly AIServiceInterface $AIService
    ) {
    }

    public function handle(User $user, string $prompt): array
    {
        $attributes = $this->AIService->generatePositionFromPrompt($user, $prompt);

        return collect($attributes)
            ->map(function (mixed $value, string $key): mixed {
                return $this->positionTransformer->transformField(Position::class, $key, $value);
            })
            ->all();
    }
}
