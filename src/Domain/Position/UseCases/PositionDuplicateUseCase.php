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

class PositionDuplicateUseCase extends UseCase
{
    public function __construct(
        private readonly ModelHasPositionRepositoryInterface $modelHasPositionRepository,
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function handle(User $user, Position $position): Position
    {
        $position->loadMissing([
            'hiringManagers',
            'approvers',
            'externalApprovers',
        ]);

        $input = new PositionStoreInput(
            company: $user->loadMissing('company')->company,
            user: $user,
            approveUntil: $position->approve_until,
            approvalRound: null,
            name: sprintf('%s: %s', __('common.copy'), $position->name),
            department: $position->department,
            field: $position->field,
            jobSeatsNum: $position->job_seats_num,
            startDate: $position->start_date,
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

        $hiringManagers = $position->hiringManagers;
        $approvers = $position->approvers;
        $externalApprovers = $position->externalApprovers;

        return DB::transaction(function () use (
            $user,
            $position,
            $input,
            $hiringManagers,
            $approvers,
            $externalApprovers,
        ): Position {
            $position = $this->positionRepository->store($input);

            $this->modelHasPositionRepository->storeMany($position, $hiringManagers, PositionRoleEnum::HIRING_MANAGER);
            $this->modelHasPositionRepository->storeMany($position, $approvers, PositionRoleEnum::APPROVER);
            $this->modelHasPositionRepository->storeMany($position, $externalApprovers, PositionRoleEnum::EXTERNAL_APPROVER);

            return $position;
        }, attempts: 5);
    }
}
