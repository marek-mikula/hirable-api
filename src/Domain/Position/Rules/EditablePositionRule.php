<?php

declare(strict_types=1);

namespace Domain\Position\Rules;

use Closure;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class EditablePositionRule implements ValidationRule
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Position::query()
            ->userCanEdit($this->user)
            ->exists();

        if ($exists) {
            return;
        }

        $fail('validation.exists')->translate();
    }
}
