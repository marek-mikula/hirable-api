<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

abstract class ModelTransformer implements ModelTransformerInterface
{
    protected function transformCarbon(string $value, string $format): ?Carbon
    {
        try {
            return Carbon::createFromFormat($format, $value);
        } catch (InvalidFormatException) {
            return null;
        }
    }
}
