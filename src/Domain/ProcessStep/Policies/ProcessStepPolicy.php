<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\User\Models\User;

class ProcessStepPolicy
{
    public function store(User $user): bool
    {
        return $user->company_role == RoleEnum::ADMIN;
    }
}
