<?php

declare(strict_types=1);

namespace Domain\User\Rules;

use Closure;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Builders\ModelHasPositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class UserOnPositionRule implements ValidationRule
{
    /**
     * @param PositionRoleEnum[] $roles
     */
    public function __construct(
        private readonly Position $position,
        private readonly array $roles,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::query()
            ->whereHas('positionModels', function (ModelHasPositionBuilder $query): void {
                $query->where('position_id', $this->position->id);

                if (!empty($this->roles)) {
                    $query->whereIn('role', $this->roles);
                }
            });

        $exists = $query->where('id', (int) $value)->exists();

        if ($exists) {
            return;
        }

        $fail('validation.exists')->translate();
    }
}
