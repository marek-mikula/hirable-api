<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Input\PositionStoreInput;
use Domain\Position\Repositories\Input\PositionUpdateInput;

class PositionRepository implements PositionRepositoryInterface
{
    public function findBy(array $wheres, array $with = []): ?Position
    {
        /** @var Position|null $position */
        $position = Position::query()
            ->with($with)
            ->where($wheres)
            ->first();

        return $position;
    }

    public function store(PositionStoreInput $input): Position
    {
        $position = new Position();

        $position->company_id = $input->company->id;
        $position->user_id = $input->user->id;
        $position->name = $input->name;
        $position->extern_name = $input->externName;
        $position->state = PositionStateEnum::DRAFT;
        $position->approve_until = $input->approveUntil;
        $position->approve_message = $input->approveMessage;
        $position->department = $input->department;
        $position->field = $input->field;
        $position->job_seats_num = $input->jobSeatsNum;
        $position->description = $input->description;
        $position->address = $input->address;
        $position->salary_from = $input->salaryFrom;
        $position->salary_to = $input->salaryTo;
        $position->salary_type = $input->salaryType;
        $position->salary_frequency = $input->salaryFrequency;
        $position->salary_currency = $input->salaryCurrency;
        $position->salary_var = $input->salaryVar;
        $position->min_education_level = $input->minEducationLevel;
        $position->education_field = $input->educationField;
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
        $position->experience_weight = $input->experienceWeight;
        $position->education_weight = $input->educationWeight;
        $position->share_salary = $input->shareSalary;
        $position->share_contact = $input->shareContact;
        $position->tags = $input->tags;

        throw_if(!$position->save(), RepositoryException::stored(Position::class));

        $position->setRelation('user', $input->user);
        $position->setRelation('company', $input->company);

        return $position;
    }

    public function update(Position $position, PositionUpdateInput $input): Position
    {
        $position->name = $input->name;
        $position->extern_name = $input->externName;
        $position->approve_until = $input->approveUntil;
        $position->approve_message = $input->approveMessage;
        $position->department = $input->department;
        $position->field = $input->field;
        $position->job_seats_num = $input->jobSeatsNum;
        $position->description = $input->description;
        $position->address = $input->address;
        $position->salary_from = $input->salaryFrom;
        $position->salary_to = $input->salaryTo;
        $position->salary_type = $input->salaryType;
        $position->salary_frequency = $input->salaryFrequency;
        $position->salary_currency = $input->salaryCurrency;
        $position->salary_var = $input->salaryVar;
        $position->min_education_level = $input->minEducationLevel;
        $position->education_field = $input->educationField;
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
        $position->experience_weight = $input->experienceWeight;
        $position->education_weight = $input->educationWeight;
        $position->share_salary = $input->shareSalary;
        $position->share_contact = $input->shareContact;
        $position->tags = $input->tags;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function updateState(Position $position, PositionStateEnum $state): Position
    {
        throw_if(
            condition: $position->state === $state,
            exception: RepositoryException::updated(Position::class)
        );

        throw_if(
            condition: !in_array($state, $position->state->getNextStates()),
            exception: RepositoryException::updated(Position::class)
        );

        $position->state = $state;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function delete(Position $position): void
    {
        throw_if(!$position->delete(), RepositoryException::deleted(Position::class));
    }

    public function updateApproveRound(Position $position, int $round): Position
    {
        $position->approve_round = $round;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }

    public function setTokens(
        Position $position,
        string $commonToken,
        string $internToken,
        string $referralToken
    ): Position {
        $position->common_token = $commonToken;
        $position->intern_token = $internToken;
        $position->referral_token = $referralToken;

        throw_if(!$position->save(), RepositoryException::updated(Position::class));

        return $position;
    }
}
