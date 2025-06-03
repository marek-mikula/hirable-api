<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class PositionIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        return Position::query()
            ->where('company_id', $user->company_id)
            ->where(function (PositionBuilder $query) use ($user) {
                $query
                    // my own positions
                    ->where('user_id', $user->id)

                    // positions where I am hiring manager
                    ->orWhereExists(function (Builder $query) use ($user): void {
                        $query
                            ->selectRaw(1)
                            ->from('model_has_positions')
                            ->whereColumn('model_has_positions.position_id', 'positions.id')
                            ->where('model_has_positions.model_type', User::class)
                            ->where('model_has_positions.model_id', $user->id)
                            ->where('model_has_positions.role', PositionRoleEnum::HIRING_MANAGER->value);
                    })

                    // positions where I am approver and I have
                    // not decided yet
                    ->orWhereExists(function (Builder $query) use ($user): void {
                        $query
                            ->selectRaw(1)
                            ->from('model_has_positions')
                            ->join('position_approvals', 'position_approvals.model_has_position_id', '=', 'model_has_positions.id')
                            ->whereColumn('model_has_positions.position_id', 'positions.id')
                            ->where('model_has_positions.model_type', User::class)
                            ->where('model_has_positions.model_id', $user->id)
                            ->where('model_has_positions.role', PositionRoleEnum::APPROVER->value)
                            ->where('position_approvals.state', PositionApprovalStateEnum::PENDING->value);
                    });
            })
            ->when($gridQuery->hasSearchQuery(), function (PositionBuilder $query) use ($gridQuery): void {
                $query->where(function (PositionBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('name', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('department', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (PositionBuilder $query) use ($gridQuery): void {
                // todo rewrite to common logic
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
                    } elseif ($column === 'state') {
                        $query->orderBy('state', $order->value);
                    } elseif ($column === 'approvalState') {
                        $query->orderBy('approval_state', $order->value);
                    } elseif ($column === 'name') {
                        $query->orderBy('name', $order->value);
                    } elseif ($column === 'lastname') {
                        $query->orderBy('lastname', $order->value);
                    } elseif ($column === 'department') {
                        $query->orderBy('department', $order->value);
                    } elseif ($column === 'createdAt') {
                        $query->orderBy('created_at', $order->value);
                    }
                }
            }, function (PositionBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
