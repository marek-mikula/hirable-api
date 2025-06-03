<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Notifications\PositionApprovalReminderNotification;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Support\Token\Models\Token;

class PositionApprovalNotifyUseCase extends UseCase
{
    public function __construct(
        private readonly PositionApprovalRepositoryInterface $positionApprovalRepository
    ) {
    }

    /**
     * @param Collection<PositionApproval> $approvals
     */
    public function handle(Collection $approvals): void
    {
        $externalApprovals = $approvals->filter(fn (PositionApproval $approval) => $approval->modelHasPosition->is_external);

        // we need to find the tokens for external approvals
        if ($externalApprovals->isNotEmpty()) {
            $tokens = Token::query()
                ->valid()
                ->whereIn('data->approvalId', $externalApprovals->pluck('id'))
                ->get()
                ->keyBy(fn (Token $token) => (int) $token->getDataValue('approvalId'));
        } else {
            $tokens = modelCollection(Token::class);
        }

        DB::transaction(function () use (
            $approvals,
            $tokens,
        ): void {
            /** @var PositionApproval $approval */
            foreach ($approvals as $approval) {
                $token = null;

                if ($approval->modelHasPosition->is_external) {
                    throw_if(!$token = $tokens->get($approval->id), new \Exception(sprintf('Cannot send reminder without a token for approval %d', $approval->id)));
                }

                $approval->modelHasPosition->model->notify(new PositionApprovalReminderNotification(
                    position: $approval->position,
                    token: $token,
                ));
            }

            $this->positionApprovalRepository->setNotifiedAt($approvals);
        }, attempts: 5);
    }
}
