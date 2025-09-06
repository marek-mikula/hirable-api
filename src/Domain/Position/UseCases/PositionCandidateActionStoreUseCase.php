<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Position\Enums\ActionOperationEnum;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Http\Request\Data\ActionData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Input\PositionCandidateActionStoreInput;
use Domain\Position\Repositories\PositionCandidateActionRepositoryInterface;
use Domain\ProcessStep\Services\ProcessStepActionService;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionCandidateActionStoreUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateActionRepositoryInterface $positionCandidateActionRepository,
        private readonly ProcessStepActionService $processStepActionService,
    ) {
    }

    public function handle(User $user, Position $position, PositionCandidate $positionCandidate, ActionData $data): PositionCandidateAction
    {
        $this->validate($positionCandidate, $data);

        $state = $data->operation === ActionOperationEnum::FINISH ? ActionStateEnum::FINISHED : $data->type->getDefaultState();

        $input = new PositionCandidateActionStoreInput(
            positionProcessStep: $positionCandidate->step,
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
            interviewResult: $data->interviewResult,
            assessmentCenterResult: $data->assessmentCenterResult,
            rejectedByCandidate: $data->rejectedByCandidate,
            rejectionReason: $data->rejectionReason,
            refusalReason: $data->refusalReason,
            taskType: $data->taskType,
            taskResult: $data->taskResult,
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

        return DB::transaction(function () use ($input, $state): PositionCandidateAction {
            $positionCandidateAction = $this->positionCandidateActionRepository->store($input);

            if ($positionCandidateAction->state !== $state) {
                $positionCandidateAction = $this->positionCandidateActionRepository->setState($positionCandidateAction, $state);
            }

            return $positionCandidateAction;
        }, attempts: 5);
    }

    private function validate(PositionCandidate $positionCandidate, ActionData $data): void
    {
        $allowedActions = $this->processStepActionService->getAllowedActionsForStep($positionCandidate->step->step);

        if (!in_array($data->type, $allowedActions)) {
            throw new HttpException(responseCode: ResponseCodeEnum::NOT_SUFFICIENT_STEP);
        }

        if (!in_array($data->type, $this->processStepActionService->getSingleActions())) {
            return;
        }

        $duplicateExists = $this->positionCandidateActionRepository->existsByTypeAndState($positionCandidate, $data->type, [
            ActionStateEnum::ACTIVE,
            ActionStateEnum::FINISHED,
        ]);

        if ($duplicateExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::ACTION_EXISTS);
        }
    }
}
