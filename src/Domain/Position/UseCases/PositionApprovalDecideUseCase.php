<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Http\Request\Data\PositionApprovalDecideData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionApprovalDecideInput;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\Token\Repositories\TokenRepositoryInterface;

class PositionApprovalDecideUseCase extends UseCase
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(User|CompanyContact $decidedBy, Position $position, PositionApproval $approval, PositionApprovalDecideData $data): PositionApproval
    {
        $approval->loadMissing('token');

        $input = new PositionApprovalDecideInput(
            state: $data->state,
            note: $data->note,
        );

        return DB::transaction(function () use (
            $decidedBy,
            $position,
            $approval,
            $input,
        ): PositionApproval {
            $approval = $this->positionApprovalRepository->decide($approval, $input);

            // in case of external approvers, we need to
            // disable the token which was used for approval
            if ($approval->token) {
                $this->tokenRepository->markUsed($approval->token);
            }

            return $approval;
        }, attempts: 5);
    }
}
