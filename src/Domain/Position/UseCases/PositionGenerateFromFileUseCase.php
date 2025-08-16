<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Context\Transformers\PositionTransformer;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;

class PositionGenerateFromFileUseCase extends UseCase
{
    public function __construct(
        private readonly PositionTransformer $positionTransformer,
        private readonly AIServiceInterface $AIService,
    ) {
    }

    public function handle(User $user, UploadedFile $file): array
    {
        $attributes = $this->AIService->generatePositionFromFile($user, $file);

        return collect($attributes)
            ->filter(function (mixed $value, string $key): bool { // filter invalid keys
                return PositionFieldEnum::tryFrom($key) !== null;
            })
            ->map(function (mixed $value, string $key): mixed {
                return $this->positionTransformer->transformField(Position::class, $key, $value);
            })
            ->all();
    }
}
