<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileCopier;

class PositionDuplicateUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly FileCopier $fileCopier,
    ) {
    }

    public function handle(User $user, Position $position): Position
    {
        $position->loadMissing([
            'hiringManagers',
            'recruiters',
            'approvers',
            'externalApprovers',
            'files',
        ]);

        $input = new PositionStoreInput(
            company: $user->loadMissing('company')->company,
            user: $user,
            approveUntil: $position->approve_until,
            approveMessage: $position->approve_message,
            name: sprintf('%s: %s', __('common.copy'), $position->name),
            department: $position->department,
            field: $position->field,
            jobSeatsNum: $position->job_seats_num,
            description: $position->description,
            isTechnical: $position->is_technical,
            address: $position->address,
            salaryFrom: $position->salary_from,
            salaryTo: $position->salary_to,
            salaryType: $position->salary_type,
            salaryFrequency: $position->salary_frequency,
            salaryCurrency: $position->salary_currency,
            salaryVar: $position->salary_var,
            minEducationLevel: $position->min_education_level,
            seniority: $position->seniority,
            experience: $position->experience,
            hardSkills: $position->hard_skills,
            organisationSkills: $position->organisation_skills,
            teamSkills: $position->team_skills,
            timeManagement: $position->time_management,
            communicationSkills: $position->communication_skills,
            leadership: $position->leadership,
            note: $position->note,
            workloads: $position->workloads,
            employmentRelationships: $position->employment_relationships,
            employmentForms: $position->employment_forms,
            benefits: $position->benefits,
            languageRequirements: $position->language_requirements,
            hardSkillsWeight: $position->hard_skills_weight,
            softSkillsWeight: $position->soft_skills_weight,
            languageSkillsWeight: $position->language_skills_weight,
        );

        return DB::transaction(function () use (
            $user,
            $position,
            $input,
        ): Position {
            $newPosition = $this->positionRepository->store($input);

            if ($position->hiringManagers->isNotEmpty()) {
                $this->modelHasPositionRepository->storeMany($newPosition, $position->hiringManagers, PositionRoleEnum::HIRING_MANAGER);
            }

            if ($position->recruiters->isNotEmpty()) {
                $this->modelHasPositionRepository->storeMany($newPosition, $position->recruiters, PositionRoleEnum::RECRUITER);
            }

            if ($position->approvers->isNotEmpty()) {
                $this->modelHasPositionRepository->storeMany($newPosition, $position->approvers, PositionRoleEnum::APPROVER);
            }

            if ($position->externalApprovers->isNotEmpty()) {
                $this->modelHasPositionRepository->storeMany($newPosition, $position->externalApprovers, PositionRoleEnum::EXTERNAL_APPROVER);
            }

            if ($position->files->isNotEmpty()) {
                $this->fileCopier->copyFiles(
                    fileable: $newPosition,
                    type: FileTypeEnum::POSITION_FILE,
                    files: $position->files->all(),
                    folders: GetModelSubFoldersAction::make()->handle($newPosition),
                );
            }

            return $newPosition;
        }, attempts: 5);
    }
}
