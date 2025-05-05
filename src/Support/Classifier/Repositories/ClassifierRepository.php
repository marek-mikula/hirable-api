<?php

declare(strict_types=1);

namespace Support\Classifier\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;
use Support\Classifier\Services\ClassifierSortService;

class ClassifierRepository implements ClassifierRepositoryInterface
{
    public function __construct(
        private readonly ClassifierSortService $classifierSortService,
    ) {
    }

    public function getValuesForType(ClassifierTypeEnum $type): Collection
    {
        $collection = Classifier::query()
            ->whereType($type)
            ->get();

        return $this->classifierSortService->sort($type, $collection)->values();
    }

    public function getValuesForTypes(array $types): array
    {
        $classifiers = Classifier::query()
            ->whereType($types)
            ->get();

        /** @var array<string,Collection> $result */
        $result = [];

        // fill in the result array with empty collections
        foreach ($types as $type) {
            $result[$type->value] = (new Classifier())->newCollection();
        }

        /** @var Classifier $classifier */
        foreach ($classifiers as $classifier) {
            $result[$classifier->type->value]->push($classifier);
        }

        // sort classifier collections
        foreach ($result as $key => $collection) {
            $result[$key] = $this->classifierSortService->sort(ClassifierTypeEnum::from($key), $collection)->values();
        }

        return $result;
    }
}
