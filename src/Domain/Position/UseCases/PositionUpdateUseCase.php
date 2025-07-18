<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\PositionUpdateData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
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
            'hiringManagers',
            'recruiters',
            'approvers',
            'externalApprovers',
        ]);

        $state = match ($data->operation) {
            PositionOperationEnum::SEND_FOR_APPROVAL => PositionStateEnum::APPROVAL_PENDING,
            PositionOperationEnum::OPEN => PositionStateEnum::OPENED,
            PositionOperationEnum::SAVE => $data->hasAnyApprovers() || $position->state === PositionStateEnum::OPENED ? $position->state : PositionStateEnum::DRAFT,
        };

        $input = new PositionUpdateInput(
            name: $data->hasKey('name') ? $data->name : $position->name,
            externName: $data->hasKey('externName') ? $data->externName : $position->extern_name,
            approveUntil: $data->hasKey('approveUntil') ? $data->approveUntil : $position->approve_until,
            approveMessage: $data->hasKey('approveMessage') ? $data->approveMessage : $position->approve_message,
            department: $data->hasKey('department') ? $data->department : $position->department,
            field: $data->hasKey('field') ? $data->field : $position->field,
            jobSeatsNum: $data->hasKey('jobSeatsNum') ? $data->jobSeatsNum : $position->job_seats_num,
            description: $data->hasKey('description') ? $data->description : $position->description,
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
            languageRequirements: $data->hasKey('languageRequirements') ? array_map(fn ($requirement) => $requirement->toArray(), $data->languageRequirements) : $position->language_requirements,
            hardSkillsWeight: $data->hasKey('hardSkillsWeight') ? $data->hardSkillsWeight : $position->hard_skills_weight,
            softSkillsWeight: $data->hasKey('softSkillsWeight') ? $data->softSkillsWeight : $position->soft_skills_weight,
            languageSkillsWeight: $data->hasKey('languageSkillsWeight') ? $data->languageSkillsWeight : $position->language_skills_weight,
            shareSalary: $data->hasKey('shareSalary') ? $data->shareSalary : $position->share_salary,
            shareContact: $data->hasKey('shareContact') ? $data->shareContact : $position->share_contact,
        );

        $hiringManagers = $data->hasKey('hiringManagers') && $data->hasHiringManagers()
            ? $this->userRepository->getByIdsAndCompany($company, $data->hiringManagers)
            : modelCollection(User::class);

        $recruiters = $data->hasKey('recruiters') && $data->hasRecruiters()
            ? $this->userRepository->getByIdsAndCompany($company, $data->recruiters)
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
            $recruiters,
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->update($position, $input);

            // process models and files
            $this->processModels($position, $data, $hiringManagers, PositionRoleEnum::HIRING_MANAGER, 'hiringManagers', 'hiringManagers');
            $this->processModels($position, $data, $recruiters, PositionRoleEnum::RECRUITER, 'recruiters', 'recruiters');
            $this->processModels($position, $data, $approvers, PositionRoleEnum::APPROVER, 'approvers', 'approvers');
            $this->processModels($position, $data, $externalApprovers, PositionRoleEnum::EXTERNAL_APPROVER, 'externalApprovers', 'externalApprovers');
            $this->processFiles($position, $data);

            // process state
            if ($state !== $position->state) {
                $position = $this->positionRepository->updateState($position, $state);
            }

            return $position;
        }, attempts: 5);
    }

    /**
     * @param Collection<Model> $models
     */
    private function processModels(Position $position, PositionUpdateData $data, Collection $models, PositionRoleEnum $role, string $key, string $relation): void
    {
        if (!$data->hasKey($key)) {
            return;
        }

        $sync = $this->modelHasPositionRepository->sync(
            position: $position,
            existingModels: $position->{$relation},
            models: $models,
            role: $role
        );

        $position->setRelation($relation, $models);

        if ($sync->deleted->isEmpty() || !in_array($role, [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER])) {
            return;
        }

        $newApprovals = $position->approvals->filter(function (PositionApproval $approval) use ($sync) {
            return $approval->modelHasPosition && !$sync->deleted->some(fn (Model $model) => $model->is($approval->modelHasPosition->model));
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
