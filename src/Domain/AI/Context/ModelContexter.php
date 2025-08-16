<?php

declare(strict_types=1);

namespace Domain\AI\Context;

use Domain\AI\Context\Mappers\ModelMapper;
use Domain\AI\Services\AIConfigService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ModelContexter
{
    public function __construct(
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

            $result[$field] = $this->serializeField($model, $field, $config[$field]);
        }

        $modelKey = Str::camel(Str::afterLast($class, '\\'));

        return json_encode([$modelKey => $result]);
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
            'classifier',
            'constraint',
            'example',
        ]);
    }

    private function getModelMapper(Model $model): ModelMapper
    {
        return once(fn () => app($this->AIConfigService->getModelMapper($model)));
    }
}
