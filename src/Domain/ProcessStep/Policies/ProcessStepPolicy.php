<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Policies;

use Domain\Company\Enums\RoleEnum;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\User\Models\User;

class ProcessStepPolicy
{
    public function index(User $user): bool
    {
        return $user->company_role == RoleEnum::ADMIN;
    }

    public function store(User $user): bool
    {
        return $user->company_role == RoleEnum::ADMIN;
    }

    public function delete(User $user, ProcessStep $processStep): bool
    {
        return $user->company_id === $processStep->company_id && $user->company_role == RoleEnum::ADMIN;
    }
}
