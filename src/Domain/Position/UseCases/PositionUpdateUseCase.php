<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Http\Request\Data\PositionUpdateData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\Position\Services\PositionApprovalService;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileSaver;

class PositionUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly CompanyContactRepositoryInterface $companyContactRepository,
        private readonly PositionApprovalService $positionApprovalService,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, Position $position, PositionUpdateData $data): Position
    {
        $company = $user->loadMissing('company')->company;

        $position->loadMissing([
            'files',
            'approvals',
            'approvals.modelHasPosition',
            'approvals.modelHasPosition.model',
            'hiringManagers',
            'approvers',
            'externalApprovers',
        ]);

        $state = match ($data->operation) {
            PositionOperationEnum::SEND_FOR_APPROVAL => PositionStateEnum::APPROVAL_PENDING,
            PositionOperationEnum::OPEN => PositionStateEnum::OPENED,
            PositionOperationEnum::SAVE => $data->hasAnyApprovers() || $position->state === PositionStateEnum::OPENED ? $position->state : PositionStateEnum::DRAFT,
        };

        $input = new PositionUpdateInput(
            approvalRound: null,
            approveUntil:  $data->hasKey('approveUntil') ? $data->approveUntil : $position->approve_until,
            name: $data->hasKey('name') ? $data->name : $position->name,
            department: $data->hasKey('department') ? $data->department : $position->department,
            field: $data->hasKey('field') ? $data->field : $position->field,
            jobSeatsNum: $data->hasKey('jobSeatsNum') ? $data->jobSeatsNum : $position->job_seats_num,
            description: $data->hasKey('description') ? $data->description : $position->description,
            isTechnical: $data->hasKey('isTechnical') ? $data->isTechnical : $position->is_technical,
            address: $data->hasKey('address') ? $data->address : $position->address,
            salaryFrom: $data->hasKey('salary') ? (int) ($data->salary ?? $data->salaryFrom) : $position->salary_from,
            salaryTo: $data->hasKey('salary') ? ($data->salary ? null : $data->salaryTo) : $position->salary_to,
            salaryType: $data->hasKey('salaryType') ? $data->salaryType : $position->salary_type,
            salaryFrequency: $data->hasKey('salaryFrequency') ? $data->salaryFrequency : $position->salary_frequency,
            salaryCurrency: $data->hasKey('salaryCurrency') ? $data->salaryCurrency : $position->salary_currency,
            salaryVar: $data->hasKey('salaryVar') ? $data->salaryVar : $position->salary_var,
            minEducationLevel: $data->hasKey('minEducationLevel') ? $data->minEducationLevel : $position->min_education_level,
            seniority: $data->hasKey('seniority') ? $data->seniority : $position->seniority,
            experience: $data->hasKey('experience') ? $data->experience : $position->experience,
            hardSkills: $data->hasKey('hardSkills') ? $data->hardSkills : $position->hard_skills,
            organisationSkills: $data->hasKey('organisationSkills') ? $data->organisationSkills : $position->organisation_skills,
            teamSkills: $data->hasKey('teamSkills') ? $data->teamSkills : $position->team_skills,
            timeManagement: $data->hasKey('timeManagement') ? $data->timeManagement : $position->time_management,
            communicationSkills: $data->hasKey('communicationSkills') ? $data->communicationSkills : $position->communication_skills,
            leadership: $data->hasKey('leadership') ? $data->leadership : $position->leadership,
            note: $data->hasKey('note') ? $data->note : $position->note,
            workloads: $data->hasKey('workloads') ? $data->workloads : $position->workloads,
            employmentRelationships: $data->hasKey('employmentRelationships') ? $data->employmentRelationships : $position->employment_relationships,
            employmentForms: $data->hasKey('employmentForms') ? $data->employmentForms : $position->employment_forms,
            benefits: $data->hasKey('benefits') ? $data->benefits : $position->benefits,
            languageRequirements: array_map(fn ($requirement) => $requirement->toArray(), $data->languageRequirements),
            hardSkillsRelevance: $data->hasKey('hardSkillsRelevance') ? $data->hardSkillsRelevance : $position->hard_skills_relevance,
            softSkillsRelevance: $data->hasKey('softSkillsRelevance') ? $data->softSkillsRelevance : $position->soft_skills_relevance,
            languageSkillsRelevance: $data->hasKey('languageSkillsRelevance') ? $data->languageSkillsRelevance : $position->language_skills_relevance,
        );

        $hiringManagers = $data->hasKey('hiringManagers') && $data->hasHiringManagers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->hiringManagers)
            : modelCollection(User::class);

        $approvers = $data->hasKey('approvers') && $data->hasApprovers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->approvers)
            : modelCollection(User::class);

        $externalApprovers = $data->hasKey('externalApprovers') && $data->hasExternalApprovers()
            ? $this->companyContactRepository->getByIdsAndCompany($company, $data->externalApprovers)
            : modelCollection(CompanyContact::class);

        return DB::transaction(function () use (
            $user,
            $position,
            $data,
            $state,
            $input,
            $hiringManagers,
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->update($position, $input);

            if ($state !== $position->state) {
                $position = $this->positionRepository->updateState($position, $state);
            }

            $this->processHiringManagers($position, $data, $hiringManagers);
            $this->processApprovers($position, $data, $approvers);
            $this->processExternalApprovers($position, $data, $externalApprovers);
            $this->processFiles($position, $data);

            if ($position->wasChanged('state') && $position->state === PositionStateEnum::APPROVAL_PENDING) {
                $approvals = $this->positionApprovalService->sendForApproval($user, $position);
                $position->setRelation('approvals', $position->approvals->push(...$approvals));
            } elseif ($position->wasChanged('state') && $position->state === PositionStateEnum::OPENED) {
                PositionOpenedEvent::dispatch($position);
            }

            return $position;
        }, attempts: 5);
    }

    private function processHiringManagers(Position $position, PositionUpdateData $data, Collection $hiringManagers): void
    {
        if (!$data->hasKey('hiringManagers')) {
            return;
        }

        $hmsSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->hiringManagers,
            models: $hiringManagers,
            role: PositionRoleEnum::HIRING_MANAGER
        );

        $position->setRelation('hiringManagers', $hiringManagers);

        if ($hmsSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($hmsSync) {
            return $approval->modelHasPosition && !$hmsSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processApprovers(Position $position, PositionUpdateData $data, Collection $approvers): void
    {
        if (!$data->hasKey('approvers')) {
            return;
        }

        $approversSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->approvers,
            models: $approvers,
            role: PositionRoleEnum::APPROVER
        );

        $position->setRelation('approvers', $approvers);

        if ($approversSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($approversSync) {
            return $approval->modelHasPosition && !$approversSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processExternalApprovers(Position $position, PositionUpdateData $data, Collection $externalApprovers): void
    {
        if (!$data->hasKey('externalApprovers')) {
            return;
        }

        $externalApproversSync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->externalApprovers,
            models: $externalApprovers,
            role: PositionRoleEnum::EXTERNAL_APPROVER
        );

        $position->setRelation('externalApprovers', $externalApprovers);

        if ($externalApproversSync->deleted->isEmpty()) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($externalApproversSync) {
            return $approval->modelHasPosition && !$externalApproversSync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
        });

        $position->setRelation('approvals', $newApprovals);
    }

    private function processFiles(Position $position, PositionUpdateData $data): void
    {
        if (!$data->hasKey('files') || !$data->hasFiles()) {
            return;
        }

        $files = $this->fileSaver->saveFiles(
            fileable: $position,
            type: FileTypeEnum::POSITION_FILE,
            files: $data->getFilesData(),
            folders: GetModelSubFoldersAction::make()->handle($position)
        );

        $position->setRelation('files', $position->files->push(...$files));
    }
}
