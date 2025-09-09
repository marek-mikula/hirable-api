<?php

declare(strict_types=1);

namespace Domain\User\Rules;

use Closure;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Builders\ModelHasPositionBuilder;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\Builders\UserBuilder;
use Domain\User\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class UserOnPositionRule implements ValidationRule
{
    /**
     * @param PositionRoleEnum[] $roles
     */
    public function __construct(
        private readonly Position $position,
        private readonly array $roles = [],
        private readonly ?int $notId = null,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = User::query()
            ->where(function (UserBuilder $query): void {
                $query
                    ->whereHas('ownsPositions', function (PositionBuilder $query): void {
                        $query->where('id', $this->position->id);
                    })
                    ->orWhereHas('positionModels', function (ModelHasPositionBuilder $query): void {
                        $query->where('position_id', $this->position->id);

                        if (!empty($this->roles)) {
                            $query->whereIn('role', $this->roles);
                        }
                    });
            });

        if (!empty($this->notId)) {
            $query->where('id', '<>', $this->notId);
        }

        $exists = $query->where('id', (int) $value)->exists();

        if ($exists) {
            return;
        }

        $fail('validation.exists')->translate();
    }
}
