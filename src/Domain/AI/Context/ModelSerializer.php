<?php

declare(strict_types=1);

namespace Domain\AI\Context;

use Domain\AI\Context\Enums\FieldTypeEnum;
use Domain\AI\Context\ValueSerializers\ValueSerializer;
use Domain\AI\Services\AIConfigService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ModelSerializer
{
    public function __construct(
        private readonly AIConfigService $AIConfigService,
    ) {
    }

    public function serialize(Model $model): string
    {
        $result = [];

        foreach ($this->AIConfigService->getModelContextConfig($model) as $fieldConfig) {
            $result[] = $this->serializeField($model, $fieldConfig);
        }

        return implode(PHP_EOL, $result);
    }

    private function serializeField(Model $model, array $config): string
    {
        return sprintf('**%s**: %s', Arr::get($config, 'label'), $this->serializeValue($model, $config));
    }

    private function serializeValue(Model $model, array $config): string
    {
        $attribute = (string) Arr::get($config, 'attribute');
        $isArray = (bool) Arr::get($config, 'is_array', false);

        $serializer = $this->resolveSerializer($config);

        if (!$isArray) {
            return $serializer->serialize($model->{$attribute}, $config);
        }

        throw_if(!is_array($model->{$attribute}), new \Exception('Non-array value passed as array field.'));

        return collect($model->{$attribute})
            ->map(fn (mixed $value) => $serializer->serialize($value, $config))
            ->filter()
            ->join(',');
    }

    private function resolveSerializer(array $config): ValueSerializer
    {
        $type = FieldTypeEnum::from((string) Arr::get($config, 'type'));

        /** @var ValueSerializer $serializer */
        $serializer = app($this->AIConfigService->getModelContextSerializer($type));

        return $serializer;
    }
}
