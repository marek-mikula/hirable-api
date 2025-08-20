<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

abstract class ModelTransformer implements ModelTransformerInterface
{
    protected function toCarbon(string $value, string $format): ?Carbon
    {
        try {
            return Carbon::createFromFormat($format, $value);
        } catch (InvalidFormatException) {
            return null;
        }
    }

    protected function toCarbonFormat(string $value, string $format): ?string
    {
        try {
            return Carbon::createFromFormat($format, $value) ? $value : null;
        } catch (InvalidFormatException) {
            return null;
        }
    }
}
