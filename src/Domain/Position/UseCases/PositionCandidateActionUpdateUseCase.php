<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\ActionOperationEnum;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Http\Request\Data\ActionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Input\PositionCandidateActionUpdateInput;
use Domain\Position\Repositories\PositionCandidateActionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateActionUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateActionRepositoryInterface $positionCandidateActionRepository,
    ) {
    }

    public function handle(
        User $user,
        Position $position,
        PositionCandidate $positionCandidate,
        PositionCandidateAction $positionCandidateAction,
        ActionData $data
    ): PositionCandidateAction {
        $state = match ($data->operation) {
            ActionOperationEnum::SAVE => $positionCandidateAction->state,
            ActionOperationEnum::FINISH => ActionStateEnum::FINISHED,
            ActionOperationEnum::CANCEL => ActionStateEnum::CANCELED,
        };

        $input = new PositionCandidateActionUpdateInput(
            date: $data->date,
            timeStart: $data->timeStart,
            timeEnd: $data->timeEnd,
            place: $data->place,
            instructions: $data->instructions,
            evaluation: $data->evaluation,
            name: $data->name,
            interviewForm: $data->interviewForm,
            interviewType: $data->interviewType,
            interviewResult: $data->interviewResult,
            assessmentCenterResult: $data->assessmentCenterResult,
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

        return DB::transaction(function () use (
            $positionCandidateAction,
            $input,
            $state,
        ): PositionCandidateAction {
            $positionCandidateAction = $this->positionCandidateActionRepository->update($positionCandidateAction, $input);

            if ($positionCandidateAction->state !== $state) {
                $positionCandidateAction = $this->positionCandidateActionRepository->setState($positionCandidateAction, $state);
            }

            return $positionCandidateAction;
        }, attempts: 5);
    }
}
