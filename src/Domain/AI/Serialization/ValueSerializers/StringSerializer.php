<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\ValueSerializers;

class StringSerializer implements ValueSerializer
{
    /**
     * @param ?string $value
     */
    public function serialize(mixed $value, array $config): ?string
    {
        if (empty($value)) {
            return null;
        }

        return str((string) $value)
            ->replace(["\r", "\r\n", "\n"], ' ')
            ->replaceMatches('/ {2,}/', ' ')
            ->toString();
    }
}
