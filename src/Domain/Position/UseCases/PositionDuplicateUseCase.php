<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Input\PositionStoreInput;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Support\File\Enums\FileTypeEnum;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FileCopier;
use Support\File\Services\FilePathService;

class PositionDuplicateUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly FilePathService $filePathService,
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
            name: sprintf('%s: %s', __('common.copy'), $position->name),
            externName: $position->extern_name,
            approveUntil: $position->approve_until,
            approveMessage: $position->approve_message,
            department: $position->department,
            field: $position->field,
            jobSeatsNum: $position->job_seats_num,
            description: $position->description,
            address: $position->address,
            salaryFrom: $position->salary_from,
            salaryTo: $position->salary_to,
            salaryType: $position->salary_type,
            salaryFrequency: $position->salary_frequency,
            salaryCurrency: $position->salary_currency,
            salaryVar: $position->salary_var,
            minEducationLevel: $position->min_education_level,
            educationField: $position->education_field,
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
            experienceWeight: $position->experience_weight,
            educationWeight: $position->education_weight,
            shareSalary: $position->share_salary,
            shareContact: $position->share_contact,
            tags: $position->tags,
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
                foreach ($position->files as $file) {
                    $file = $this->fileCopier->copyFile(
                        file: $file,
                        path: $this->filePathService->getPathForModel($newPosition),
                        type: FileTypeEnum::POSITION_FILE,
                    );

                    $this->modelHasFileRepository->store($newPosition, $file);
                }
            }

            return $newPosition;
        }, attempts: 5);
    }
}
