<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use Illuminate\Support\Str;

class JsonTransformer
{
    public function transform(array $array): array
    {
        // Some AI providers (OpenAI) cannot have objects
        // with any params in their schema validation when
        // using structured outputs. So we tell the model
        // to encode every more complicated value into JSON.
        // Here we decode that JSON into array.

        return collect($array)
            ->dot()
            ->map(function (mixed $value): mixed {
                if (!is_string($value)) {
                    return $value;
                }

                if (!Str::isJson($value)) {
                    return $value;
                }

                try {
                    return json_decode($value, associative: true);
                } catch (\Exception) {
                    throw new \Exception(sprintf('Cannot parse json: %s', $value));
                }
            })
            ->undot()
            ->toArray();
    }
}
