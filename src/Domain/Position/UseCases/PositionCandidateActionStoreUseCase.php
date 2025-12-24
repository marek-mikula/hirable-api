<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
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

        $input = new PositionCandidateActionStoreInput(
            positionProcessStep: $positionCandidate->step,
            positionCandidate: $positionCandidate,
            user: $user,
            type: $data->type,
            date: $data->date,
            timeStart: $data->timeStart,
            timeEnd: $data->timeEnd,
            place: $data->place,
            evaluation: $data->evaluation,
            name: $data->name,
            interviewForm: $data->interviewForm,
            interviewType: $data->interviewType,
            rejectedByCandidate: $data->rejectedByCandidate,
            rejectionReason: $data->rejectionReason,
            refusalReason: $data->refusalReason,
            taskType: $data->taskType,
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
            realStartDate: $data->realStartDate,
            note: $data->note,
        );

        return DB::transaction(function () use ($input): PositionCandidateAction {
            return $this->positionCandidateActionRepository->store($input);
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

        $duplicateExists = $this->positionCandidateActionRepository->existsByType($positionCandidate, $data->type);

        if ($duplicateExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::ACTION_EXISTS);
        }
    }
}
