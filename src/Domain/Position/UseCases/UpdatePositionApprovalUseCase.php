<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Http\Request\Data\PositionApprovalUpdateData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class UpdatePositionApprovalUseCase extends UseCase
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
    ) {
    }

    public function handle(User $user, Position $position, PositionApproval $approval, PositionApprovalUpdateData $data): PositionApproval
    {
        $input = new PositionApprovalDecideInput(
            state: $data->state,
            note: $data->note,
        );

        return DB::transaction(function () use (
            $user,
            $position,
            $approval,
            $input,
        ): PositionApproval {
            $approval = $this->positionApprovalRepository->decide($approval, $input);

            if ($approval->state === PositionApprovalStateEnum::APPROVED) {
                PositionApprovedEvent::dispatch($position, $approval, $user);
            } elseif ($approval->state === PositionApprovalStateEnum::REJECTED) {
                PositionRejectedEvent::dispatch($position, $approval, $user);
            }

            return $approval;
        }, attempts: 5);
    }
}
