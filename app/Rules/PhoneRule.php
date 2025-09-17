<?php

declare(strict_types=1);

namespace App\Rules;

use App\Rules\Concerns\HandlesData;
use App\Services\ValidationConfigService;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneRule implements ValidationRule, DataAwareRule
{
    use HandlesData;

    public function __construct(
        private readonly string $prefixField
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!ValidationConfigService::resolve()->shouldValidatePhone()) {
            return;
        }

        if (empty($value)) {
            return;
        }

        $prefix = Arr::get($this->data, $this->prefixField);

        if (empty($prefix)) {
            return;
        }

        if ($this->validatePhoneNumber($prefix . $value)) {
            return;
        }

        $fail('validation.phone')->translate();
    }

    private function validatePhoneNumber(string $phoneNumber): bool
    {
        $util = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $util->parse($phoneNumber);
        } catch (NumberParseException) {
            return false;
        }

        return $util->isValidNumber($phoneNumber);
    }
}
