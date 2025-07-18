<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\ValidationConfigService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LinkedinRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ValidationConfigService::resolve()->shouldValidateLinkedIn()) {
            return;
        }

        if (empty($value)) {
            return;
        }

        if (preg_match('/^(https:\/\/)?(www\.)?linkedin\.com\/in\/[a-zA-Z0-9-]+\/?$/', $value)) {
            return;
        }

        $fail('validation.linkedin')->translate();
    }
}
