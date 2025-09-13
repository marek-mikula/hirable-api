<?php

declare(strict_types=1);

namespace Support\Classifier\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierConfigService;

final readonly class ClassifierCachedRepository implements ClassifierRepositoryInterface
{
    public function __construct(
        private ClassifierConfigService $classifierConfigService,
        private ClassifierRepository $classifierRepository,
    ) {
    }

    public function getValuesForType(ClassifierTypeEnum $type): Collection
    {
        $key = $this->getCacheKey($type);

        $cacheTime = now()->addSeconds($this->classifierConfigService->getCacheTime());

        return Cache::memo('file')->remember($key, $cacheTime, fn (): \Illuminate\Database\Eloquent\Collection => $this->classifierRepository->getValuesForType($type));
    }

    public function getValuesForTypes(array $types): array
    {
        $result = [];

        $missingTypes = [];

        // try to pull all values from
        // the cache
        foreach ($types as $type) {
            $collection = Cache::driver('file')->get($this->getCacheKey($type));

            if (!$collection) {
                $missingTypes[] = $type;

                continue;
            }

            $result[$type->value] = $collection;
        }

        // there is no need to retrieve
        // other types from DB and cache
        // it, we received everything
        if (empty($missingTypes)) {
            return $result;
        }

        $cacheTime = now()->addSeconds($this->classifierConfigService->getCacheTime());

        $missingValues = $this->classifierRepository->getValuesForTypes($missingTypes);

        foreach ($missingValues as $key => $collection) {
            $type = ClassifierTypeEnum::from($key);

            $key = $this->getCacheKey($type);

            Cache::driver('file')->put($key, $collection, $cacheTime);

            $result[$type->value] = $collection;
        }

        return $result;
    }

    private function getCacheKey(ClassifierTypeEnum $type): string
    {
        return sprintf('classifiers.%s', $type->value);
    }
}
