<?php

declare(strict_types=1);

namespace Support\Classifier\Services;

use Illuminate\Database\Eloquent\Collection;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

class ClassifierSortService
{
    public function __construct(
        private readonly ClassifierConfigService $classifierConfigService,
    ) {
    }

    public function sort(ClassifierTypeEnum $type, Collection $collection): Collection
    {
        $order = $this->classifierConfigService->getOrder($type);

        return $collection->sort(function (Classifier $a, Classifier $b) use ($order): int {
            $indexA = $order !== null ? array_search($a->value, $order) : false;
            $indexB = $order !== null ? array_search($b->value, $order) : false;

            // both values have priority order
            if ($indexA !== false && $indexB !== false) {
                return $indexA - $indexB;
            }

            // only A has priority – it goes up
            if ($indexA !== false) {
                return -1;
            }

            // only B has priority – it goes up
            if ($indexB !== false) {
                return 1;
            }

            // otherwise sort by alphabetical order
            return strcmp($a->label, $b->label);
        });
    }
}
