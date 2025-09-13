<?php

declare(strict_types=1);

namespace Domain\AI\Context;

use App\Enums\LanguageEnum;
use Domain\AI\Context\Mappers\ModelMapperInterface;
use Domain\AI\Services\AIConfigService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;

class ModelContexter
{
    public function __construct(
        private readonly ClassifierRepositoryInterface $classifierRepository,
        private readonly AIConfigService $AIConfigService,
    ) {
    }

    /**
     * @param \BackedEnum[] $fields
     */
    public function getModelContext(Model|string $model, array $fields = []): string
    {
        $result = [];

        $config = $this->AIConfigService->getModelContextConfig($model);

        $class = is_string($model) ? $model : $model::class;

        // if specific fields are passed, extract
        // the value from an enum objects
        //
        // if no specific fields are passed, take
        // all of them from config
        if (!empty($fields)) {
            $fields = Arr::pluck($fields, 'value');
        } else {
            $fields = array_keys($config);
        }

        foreach ($fields as $field) {
            if (!array_key_exists($field, $config)) {
                throw new \InvalidArgumentException(sprintf('Field %s has no configured context for model %s.', $field, $class));
            }

            $fieldConfig = $config[$field];

            $result[$field] = $this->serializeField($model, $field, $fieldConfig);
        }

        // add classifier enum definitions if needed
        if (is_string($model)) {
            $result = $this->transformClassifiersToEnumValues($result);
        }

        return json_encode($result);
    }

    private function serializeField(Model|string $model, string $field, array $config): array
    {
        // if model is an object, we suppose we need
        // values of this model
        if (!is_string($model)) {
            return [
                'value' => $this->getModelMapper($model)->mapField($model, $field),
                ...Arr::only($config, [
                    'label',
                    'description',
                ]),
            ];
        }

        // if model is passed as string reference, we suppose
        // we need attributes definition
        return Arr::only($config, [
            'label',
            'description',
            'schema',
        ]);
    }

    private function getModelMapper(Model $model): ModelMapperInterface
    {
        return once(fn () => app($this->AIConfigService->getModelMapper($model)));
    }

    private function transformClassifiersToEnumValues(array $attributes): array
    {
        $keys = collect($attributes)->dot()->keys();

        /** @var Collection<string,ClassifierTypeEnum> $classifiers */
        $classifiers = $keys
            ->filter(fn (string $key) => Str::endsWith($key, '.classifier'))
            ->mapWithKeys(fn (string $key) => [
                $key => ClassifierTypeEnum::from((string) Arr::get($attributes, $key)),
            ]);

        if ($classifiers->isEmpty()) {
            return $attributes;
        }

        $classifierValues = $this->classifierRepository->getValuesForTypes($classifiers->values()->toArray());

        foreach ($classifiers as $key => $type) {
            $parentKey = Str::beforeLast($key, '.');

            // unset old classifier value
            Arr::forget($attributes, $key);

            // transform classifiers into array
            $enums = withLocale(LanguageEnum::EN, fn (): array => $classifierValues[$type->value]
                ->pluck('label', 'value')
                ->toArray());

            // set new enum attributes with classifier values
            Arr::set($attributes, sprintf('%s.enum', $parentKey), $enums);
        }

        return $attributes;
    }
}
