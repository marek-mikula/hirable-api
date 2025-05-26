<?php

declare(strict_types=1);

namespace Support\Classifier\UseCases;

use App\UseCases\UseCase;
use Illuminate\Database\Eloquent\Collection;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;

class GetClassifierIndexUseCase extends UseCase
{
    public function __construct(
        private readonly ClassifierRepositoryInterface $classifierRepository,
    ) {
    }

    /**
     * @param ClassifierTypeEnum[] $types
     * @return array<string,Collection<Classifier>>
     */
    public function handle(array $types): array
    {
        // if no specific types are passed, return
        // all classifier types
        if (empty($types)) {
            $types = ClassifierTypeEnum::cases();
        }

        return $this->classifierRepository->getValuesForTypes($types);
    }
}
