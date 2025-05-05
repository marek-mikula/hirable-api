<?php

declare(strict_types=1);

namespace Support\Classifier\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

interface ClassifierRepositoryInterface
{
    /**
     * @param ClassifierTypeEnum $type
     * @return Collection<Classifier>
     */
    public function getValuesForType(ClassifierTypeEnum $type): Collection;

    /**
     * @param ClassifierTypeEnum[] $types
     * @return array<string,Collection<Classifier>>
     */
    public function getValuesForTypes(array $types): array;
}
