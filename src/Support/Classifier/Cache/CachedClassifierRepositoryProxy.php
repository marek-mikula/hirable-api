<?php

declare(strict_types=1);

namespace Support\Classifier\Cache;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Repositories\ClassifierRepository;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;
use Support\Classifier\Services\ClassifierConfigService;

class CachedClassifierRepositoryProxy implements ClassifierRepositoryInterface
{
    public function __construct(
        private readonly ClassifierConfigService $classifierConfigService,
        private readonly ClassifierRepository $classifierRepository,
        private readonly ClassifierCacheKeys $cacheKeys,
    ) {
    }

    public function getValuesForType(ClassifierTypeEnum $type): Collection
    {
        $key = $this->cacheKeys->getTypeCollectionCacheKey($type);

        $cacheTime = now()->addSeconds($this->classifierConfigService->getCacheTime());

        return Cache::memo('file')->remember($key, $cacheTime, function () use ($type) {
            return $this->classifierRepository->getValuesForType($type);
        });
    }

    public function getValuesForTypes(array $types): array
    {
        $result = [];

        $missingTypes = [];

        // try to pull all values from
        // the cache
        foreach ($types as $type) {
            $collection = Cache::driver('file')->get($this->cacheKeys->getTypeCollectionCacheKey($type));

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

            $key = $this->cacheKeys->getTypeCollectionCacheKey($type);

            Cache::driver('file')->put($key, $collection, $cacheTime);

            $result[$type->value] = $collection;
        }

        return $result;
    }
}
