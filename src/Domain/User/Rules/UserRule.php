<?php

declare(strict_types=1);

namespace Domain\User\Rules;

use Closure;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\User\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class UserRule implements ValidationRule
{
    /**
     * @param Company|null $company
     * @param RoleEnum[] $roles
     */
    public function __construct(
        private readonly ?Company $company = null,
        private readonly array $roles = [],
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::query();

        if (!empty($this->company)) {
            $query = $query->whereCompany($this->company->id);
        }

        if (!empty($this->roles)) {
            $query = $query->whereIn('company_role', $this->roles);
        }

        $exists = $query->where('id', (int) $value)->exists();

        if ($exists) {
            return;
        }

        $fail('validation.exists')->translate();
    }
}
