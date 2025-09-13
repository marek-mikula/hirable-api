<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\PositionTransformer;
use Domain\AI\Services\AIService;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;

class PositionGenerateFromFileUseCase extends UseCase
{
    public function __construct(
        private readonly PositionTransformer $positionTransformer,
        private readonly AIService $AIService,
    ) {
    }

    public function handle(User $user, UploadedFile $file): array
    {
        $attributes = $this->AIService->generatePositionFromFile($user, $file);

        return collect($attributes)
            ->filter(fn (mixed $value, string $key): bool =>
                // filter invalid keys
                PositionFieldEnum::tryFrom($key) !== null)
            ->map(fn (mixed $value, string $key): mixed => $this->positionTransformer->transformField($key, $value))
            ->all();
    }
}
