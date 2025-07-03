<?php

declare(strict_types=1);

namespace App\Rules;

use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\User\Rules\UserRule;
use Illuminate\Validation\Rule as BaseRule;

class Rule extends BaseRule
{
    /**
     * @param RoleEnum[] $roles
     */
    public static function user(?Company $company = null, array $roles = []): UserRule
    {
        return new UserRule($company, $roles);
    }
}
