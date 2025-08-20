<?php

declare(strict_types=1);

namespace Support\Token\Services;

use App\Services\Service;
use Support\Token\Enums\TokenTypeEnum;

class TokenConfigService extends Service
{
    public function getKeepDays(): int
    {
        return (int) config('token.keep_days');
    }

    public function getTokenValidity(TokenTypeEnum $type): int
    {
        return (int) config(sprintf('token.validity.%s', $type->value));
    }

    public function getTokenThrottle(TokenTypeEnum $type): ?int
    {
        $throttle = config(sprintf('token.throttle.%s', $type->value));

        return empty($throttle) ? null : (int) $throttle;
    }
}
