<?php

declare(strict_types=1);

namespace App\Services;

class ValidationConfigService extends Service
{
    public function shouldValidatePhone(): bool
    {
        return (bool) config('validation.phone');
    }

    public function shouldValidateLinkedIn(): bool
    {
        return (bool) config('validation.linkedin');
    }
}
