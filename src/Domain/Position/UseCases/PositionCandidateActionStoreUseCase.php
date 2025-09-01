<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\ActionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Inputs\PositionCandidateActionStoreInput;
use Domain\Position\Repositories\PositionCandidateActionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateActionStoreUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateActionRepositoryInterface $positionCandidateActionRepository,
    ) {
    }

    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, ActionData $data): PositionCandidateAction
    {
        $input = new PositionCandidateActionStoreInput(
            positionCandidate: $positionCandidate,
            user: $user,
            type: $data->type,
            date: $data->date,
            timeStart: $data->timeStart,
            timeEnd: $data->timeEnd,
            interviewForm: $data->interviewForm,
            interviewType: $data->interviewType,
            place: $data->place,
            testType: $data->testType,
            instructions: $data->instructions,
            result: $data->result,
            rejectedByCandidate: $data->rejectedByCandidate,
            rejectionReason: $data->rejectionReason,
            refusalReason: $data->refusalReason,
            name: $data->name,
            note: $data->note,
        );

        return DB::transaction(function () use ($input): PositionCandidateAction {
            return $this->positionCandidateActionRepository->store($input);
        }, attempts: 5);
    }
}
