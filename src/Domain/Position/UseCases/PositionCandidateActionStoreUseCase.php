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
            place: $data->place,
            instructions: $data->instructions,
            evaluation: $data->evaluation,
            name: $data->name,
            interviewForm: $data->interviewForm,
            interviewType: $data->interviewType,
            unavailable: $data->unavailable,
            noShow: $data->noShow,
            rejectedByCandidate: $data->rejectedByCandidate,
            rejectionReason: $data->rejectionReason,
            refusalReason: $data->refusalReason,
            testType: $data->testType,
            offerState: $data->offerState,
            offerJobTitle: $data->offerJobTitle,
            offerCompany: $data->offerCompany,
            offerEmploymentForms: $data->offerEmploymentForms,
            offerPlace: $data->offerPlace,
            offerSalary: $data->offerSalary,
            offerSalaryCurrency: $data->offerSalaryCurrency,
            offerSalaryFrequency: $data->offerSalaryFrequency,
            offerWorkload: $data->offerWorkload,
            offerEmploymentRelationship: $data->offerEmploymentRelationship,
            offerStartDate: $data->offerStartDate,
            offerEmploymentDuration: $data->offerEmploymentDuration,
            offerCertainPeriodTo: $data->offerCertainPeriodTo,
            offerTrialPeriod: $data->offerTrialPeriod,
            offerCandidateNote: $data->offerCandidateNote,
            realStartDate: $data->realStartDate,
            note: $data->note,
        );

        return DB::transaction(function () use ($input): PositionCandidateAction {
            return $this->positionCandidateActionRepository->store($input);
        }, attempts: 5);
    }
}
