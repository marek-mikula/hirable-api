<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\ValueSerializers;

interface ValueSerializer
{
    public function serialize(mixed $value, array $config): ?string;
}
