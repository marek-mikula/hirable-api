<?php

declare(strict_types=1);

namespace Domain\AI\Context\ValueSerializers;

interface ValueSerializer
{
    public function serialize(mixed $value, array $config): ?string;
}
