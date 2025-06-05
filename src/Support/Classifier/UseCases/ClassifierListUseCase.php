<?php

declare(strict_types=1);

namespace Support\Classifier\UseCases;

use App\UseCases\UseCase;
use Illuminate\Database\Eloquent\Collection;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;

class ClassifierListUseCase extends UseCase
{
    public function __construct(
        private readonly ClassifierRepositoryInterface $classifierRepository,
    ) {
    }

    public function handle(ClassifierTypeEnum $type): Collection
    {
        return $this->classifierRepository->getValuesForType($type);
    }
}
