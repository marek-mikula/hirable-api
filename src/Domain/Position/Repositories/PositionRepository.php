<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;

class PositionRepository implements PositionRepositoryInterface
{
    public function store(PositionStoreInput $input): Position
    {
        $position = new Position();

        $position->company_id = $input->company->id;
        $position->user_id = $input->user->id;
        $position->state = PositionStateEnum::DRAFT;
        $position->approval_round = $input->approvalRound;
        $position->approve_until = $input->approveUntil;
        $position->name = $input->name;
        $position->department = $input->department;
        $position->field = $input->field;
        $position->job_seats_num = $input->jobSeatsNum;
        $position->description = $input->description;
        $position->is_technical = $input->isTechnical;
        $position->address = $input->address;
        $position->salary_from = $input->salaryFrom;
        $position->salary_to = $input->salaryTo;
        $position->salary_type = $input->salaryType;
        $position->salary_frequency = $input->salaryFrequency;
        $position->salary_currency = $input->salaryCurrency;
        $position->salary_var = $input->salaryVar;
        $position->min_education_level = $input->minEducationLevel;
        $position->seniority = $input->seniority;
        $position->experience = $input->experience;
        $position->hard_skills = $input->hardSkills;
        $position->organisation_skills = $input->organisationSkills;
        $position->team_skills = $input->teamSkills;
        $position->time_management = $input->timeManagement;
        $position->communication_skills = $input->communicationSkills;
        $position->leadership = $input->leadership;
        $position->note = $input->note;
        $position->workloads = $input->workloads;
        $position->employment_relationships = $input->employmentRelationships;
        $position->employment_forms = $input->employmentForms;
        $position->benefits = $input->benefits;
        $position->language_requirements = $input->languageRequirements;
        $position->hard_skills_weight = $input->hardSkillsWeight;
        $position->soft_skills_weight = $input->softSkillsWeight;
        $position->language_skills_weight = $input->languageSkillsWeight;

        throw_if(!$position->save(), RepositoryException::stored(Position::class));

        $position->setRelation('user', $input->user);
        $position->setRelation('company', $input->company);

        return $position;
    }

    public function update(Position $position, PositionUpdateInput $input): Position
    {
        $position->approval_round = $input->approvalRound;
        $position->approve_until = $input->approveUntil;
        $position->name = $input->name;
        $position->department = $input->department;
        $position->field = $input->field;
        $position->job_seats_num = $input->jobSeatsNum;
        $position->description = $input->description;
        $position->is_technical = $input->isTechnical;
        $position->address = $input->address;
        $position->salary_from = $input->salaryFrom;
        $position->salary_to = $input->salaryTo;
        $position->salary_type = $input->salaryType;
        $position->salary_frequency = $input->salaryFrequency;
        $position->salary_currency = $input->salaryCurrency;
        $position->salary_var = $input->salaryVar;
        $position->min_education_level = $input->minEducationLevel;
        $position->seniority = $input->seniority;
        $position->experience = $input->experience;
        $position->hard_skills = $input->hardSkills;
        $position->organisation_skills = $input->organisationSkills;
        $position->team_skills = $input->teamSkills;
        $position->time_management = $input->timeManagement;
        $position->communication_skills = $input->communicationSkills;
        $position->leadership = $input->leadership;
        $position->note = $input->note;
        $position->workloads = $input->workloads;
        $position->employment_relationships = $input->employmentRelationships;
        $position->employment_forms = $input->employmentForms;
        $position->benefits = $input->benefits;
        $position->language_requirements = $input->languageRequirements;
        $position->hard_skills_weight = $input->hardSkillsWeight;
        $position->soft_skills_weight = $input->softSkillsWeight;
        $position->language_skills_weight = $input->languageSkillsWeight;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function updateState(Position $position, PositionStateEnum $state): Position
    {
        // validate state
        throw_if(
            condition: $position->state !== $state && !in_array($state, $position->state->getNextStates()),
            exception: RepositoryException::updated(Position::class)
        );

        $position->state = $state;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function updateApprovalRound(Position $position, ?int $round): Position
    {
        $position->approval_round = $round;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function delete(Position $position): void
    {
        throw_if(!$position->delete(), RepositoryException::deleted(Position::class));
    }
}
