<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\ValueSerializers;

use Illuminate\Support\Arr;

class CustomSerializer implements ValueSerializer
{
    public function serialize(mixed $value, array $config): ?string
    {
        /** @var ValueSerializer $serializer */
        $serializer = app((string) Arr::get($config, 'serializer'));

        return $serializer->serialize($value, $config);
    }
}
