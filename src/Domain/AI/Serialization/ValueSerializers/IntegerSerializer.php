<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\ValueSerializers;

class IntegerSerializer implements ValueSerializer
{
    /**
     * @param ?int $value
     */
    public function serialize(mixed $value, array $config): ?string
    {
        return $value === null ? null : (string) $value;
    }
}
