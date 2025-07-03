<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\User\Models\User;

class PositionBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', $id);
    }

    public function approvalExpired(): static
    {
        return $this->where(function (PositionBuilder $query): void {
            $query
                ->whereDate('approve_until', '<', now())
                ->where('state', PositionStateEnum::APPROVAL_PENDING->value);
        });
    }

    public function userCanSee(User $user): static
    {
        return $this->where(function (PositionBuilder $query) use ($user) {
            $query
                // positions where I am the owner
                ->where('user_id', $user->id)

                // positions where I am hiring manager or recruiter
                ->orWhereExists(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($user): void {
                    $query
                        ->selectRaw(1)
                        ->from('model_has_positions')
                        ->whereColumn('model_has_positions.position_id', 'positions.id')
                        ->where('model_has_positions.model_type', User::class)
                        ->where('model_has_positions.model_id', $user->id)
                        ->whereIn('model_has_positions.role', [
                            PositionRoleEnum::RECRUITER,
                            PositionRoleEnum::HIRING_MANAGER,
                        ]);
                })

                // positions where I am approver and I have
                // not decided yet
                ->orWhereExists(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($user): void {
                    $query
                        ->selectRaw(1)
                        ->from('model_has_positions')
                        ->join('position_approvals', 'position_approvals.model_has_position_id', '=', 'model_has_positions.id')
                        ->whereColumn('model_has_positions.position_id', 'positions.id')
                        ->where('model_has_positions.model_type', User::class)
                        ->where('model_has_positions.model_id', $user->id)
                        ->where('model_has_positions.role', PositionRoleEnum::APPROVER)
                        ->where('position_approvals.state', PositionApprovalStateEnum::PENDING);
                });
        });
    }
}
