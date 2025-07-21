<?php

declare(strict_types=1);

namespace Domain\AI\Serialization;

use Domain\AI\Serialization\ValueSerializers\ValueSerializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ModelSerializer
{
    public function serialize(Model $model): string
    {
        $config = config(sprintf('ai.context.models.%s', $model::class));

        throw_if(empty($config), new \Exception(sprintf('Undefined context definition for class %s.', $model::class)));

        $result = [];

        foreach ($config as $fieldConfig) {
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
        /** @var class-string<ValueSerializer> $serializer */
        $serializer = config(sprintf('ai.context.serializers.%s', Arr::get($config, 'type')));

        /** @var ValueSerializer $serializer */
        $serializer = app($serializer);

        return $serializer;
    }
}
