<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionStoreInput;
use Domain\Position\Repositories\Inputs\PositionUpdateInput;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\User\Models\User;

use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Domain\Position\Repositories\PositionRepository::store */
it('tests store method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $company = Company::factory()->create();
    $user = User::factory()->ofCompany($company, RoleEnum::RECRUITER)->create();

    $input = new PositionStoreInput(
        company: $company,
        user: $user,
        approveUntil: null,
        approveMessage: fake()->text(500),
        name: fake()->jobTitle,
        department: fake()->word,
        field: fake()->word,
        jobSeatsNum: fake()->numberBetween(0, 10),
        description: fake()->text(2000),
        isTechnical: fake()->boolean,
        address: fake()->words(asText: true),
        salaryFrom: fake()->numberBetween(1, 50000),
        salaryTo: fake()->numberBetween(50000, 100000),
        salaryType: fake()->word,
        salaryFrequency: fake()->word,
        salaryCurrency: fake()->word,
        salaryVar: fake()->word,
        minEducationLevel: fake()->word,
        seniority: fake()->word,
        experience: fake()->numberBetween(1, 5),
        hardSkills: fake()->text(2000),
        organisationSkills: fake()->numberBetween(0, 10),
        teamSkills: fake()->numberBetween(0, 10),
        timeManagement: fake()->numberBetween(0, 10),
        communicationSkills: fake()->numberBetween(0, 10),
        leadership: fake()->numberBetween(0, 10),
        note: fake()->text(2000),
        workloads: fake()->words(fake()->numberBetween(0, 5)),
        employmentRelationships: fake()->words(fake()->numberBetween(0, 5)),
        employmentForms: fake()->words(fake()->numberBetween(0, 5)),
        benefits: fake()->words(fake()->numberBetween(0, 5)),
        languageRequirements: fake()->words(fake()->numberBetween(0, 5)),
        hardSkillsWeight: fake()->numberBetween(0, 10),
        softSkillsWeight: fake()->numberBetween(0, 10),
        languageSkillsWeight: fake()->numberBetween(0, 10),
    );

    $position = $repository->store($input);

    assertSame($input->company->id, $position->company_id);
    assertSame($input->user->id, $position->user_id);
    assertSame($input->approveUntil, $position->approve_until);
    assertSame($input->approveMessage, $position->approve_message);
    assertSame($input->name, $position->name);
    assertSame($input->department, $position->department);
    assertSame($input->field, $position->field);
    assertSame($input->jobSeatsNum, $position->job_seats_num);
    assertSame($input->description, $position->description);
    assertSame($input->isTechnical, $position->is_technical);
    assertSame($input->address, $position->address);
    assertSame($input->salaryFrom, $position->salary_from);
    assertSame($input->salaryTo, $position->salary_to);
    assertSame($input->salaryType, $position->salary_type);
    assertSame($input->salaryFrequency, $position->salary_frequency);
    assertSame($input->salaryCurrency, $position->salary_currency);
    assertSame($input->salaryVar, $position->salary_var);
    assertSame($input->minEducationLevel, $position->min_education_level);
    assertSame($input->seniority, $position->seniority);
    assertSame($input->experience, $position->experience);
    assertSame($input->hardSkills, $position->hard_skills);
    assertSame($input->organisationSkills, $position->organisation_skills);
    assertSame($input->teamSkills, $position->team_skills);
    assertSame($input->timeManagement, $position->time_management);
    assertSame($input->communicationSkills, $position->communication_skills);
    assertSame($input->leadership, $position->leadership);
    assertSame($input->note, $position->note);
    assertSame($input->workloads, $position->workloads);
    assertSame($input->employmentRelationships, $position->employment_relationships);
    assertSame($input->employmentForms, $position->employment_forms);
    assertSame($input->benefits, $position->benefits);
    assertSame($input->languageRequirements, $position->language_requirements);
    assertSame($input->hardSkillsWeight, $position->hard_skills_weight);
    assertSame($input->softSkillsWeight, $position->soft_skills_weight);
    assertSame($input->languageSkillsWeight, $position->language_skills_weight);

    assertTrue($position->relationLoaded('company'));
    assertTrue($position->relationLoaded('user'));
});

/** @covers \Domain\Position\Repositories\PositionRepository::update */
it('tests update method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $company = Company::factory()->create();
    $position = Position::factory()->ofCompany($company)->create();

    $input = new PositionUpdateInput(
        approveUntil: null,
        approveMessage: fake()->text(500),
        name: fake()->jobTitle,
        department: fake()->word,
        field: fake()->word,
        jobSeatsNum: fake()->numberBetween(0, 10),
        description: fake()->text(2000),
        isTechnical: fake()->boolean,
        address: fake()->words(asText: true),
        salaryFrom: fake()->numberBetween(1, 50000),
        salaryTo: fake()->numberBetween(50000, 100000),
        salaryType: fake()->word,
        salaryFrequency: fake()->word,
        salaryCurrency: fake()->word,
        salaryVar: fake()->word,
        minEducationLevel: fake()->word,
        seniority: fake()->word,
        experience: fake()->numberBetween(1, 5),
        hardSkills: fake()->text(2000),
        organisationSkills: fake()->numberBetween(0, 10),
        teamSkills: fake()->numberBetween(0, 10),
        timeManagement: fake()->numberBetween(0, 10),
        communicationSkills: fake()->numberBetween(0, 10),
        leadership: fake()->numberBetween(0, 10),
        note: fake()->text(2000),
        workloads: fake()->words(fake()->numberBetween(0, 5)),
        employmentRelationships: fake()->words(fake()->numberBetween(0, 5)),
        employmentForms: fake()->words(fake()->numberBetween(0, 5)),
        benefits: fake()->words(fake()->numberBetween(0, 5)),
        languageRequirements: fake()->words(fake()->numberBetween(0, 5)),
        hardSkillsWeight: fake()->numberBetween(0, 10),
        softSkillsWeight: fake()->numberBetween(0, 10),
        languageSkillsWeight: fake()->numberBetween(0, 10),
    );

    $position = $repository->update($position, $input);

    assertSame($input->approveUntil, $position->approve_until);
    assertSame($input->approveMessage, $position->approve_message);
    assertSame($input->name, $position->name);
    assertSame($input->department, $position->department);
    assertSame($input->field, $position->field);
    assertSame($input->jobSeatsNum, $position->job_seats_num);
    assertSame($input->description, $position->description);
    assertSame($input->isTechnical, $position->is_technical);
    assertSame($input->address, $position->address);
    assertSame($input->salaryFrom, $position->salary_from);
    assertSame($input->salaryTo, $position->salary_to);
    assertSame($input->salaryType, $position->salary_type);
    assertSame($input->salaryFrequency, $position->salary_frequency);
    assertSame($input->salaryCurrency, $position->salary_currency);
    assertSame($input->salaryVar, $position->salary_var);
    assertSame($input->minEducationLevel, $position->min_education_level);
    assertSame($input->seniority, $position->seniority);
    assertSame($input->experience, $position->experience);
    assertSame($input->hardSkills, $position->hard_skills);
    assertSame($input->organisationSkills, $position->organisation_skills);
    assertSame($input->teamSkills, $position->team_skills);
    assertSame($input->timeManagement, $position->time_management);
    assertSame($input->communicationSkills, $position->communication_skills);
    assertSame($input->leadership, $position->leadership);
    assertSame($input->note, $position->note);
    assertSame($input->workloads, $position->workloads);
    assertSame($input->employmentRelationships, $position->employment_relationships);
    assertSame($input->employmentForms, $position->employment_forms);
    assertSame($input->benefits, $position->benefits);
    assertSame($input->languageRequirements, $position->language_requirements);
    assertSame($input->hardSkillsWeight, $position->hard_skills_weight);
    assertSame($input->softSkillsWeight, $position->soft_skills_weight);
    assertSame($input->languageSkillsWeight, $position->language_skills_weight);
});

/** @covers \Domain\Position\Repositories\PositionRepository::updateState */
it('tests updateState method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->ofState(PositionStateEnum::DRAFT)->create();

    $position = $repository->updateState($position, PositionStateEnum::OPENED);

    assertSame(PositionStateEnum::OPENED, $position->state);
});

/** @covers \Domain\Position\Repositories\PositionRepository::delete */
it('tests delete method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->create();

    $repository->delete($position);

    assertModelMissing($position);
});

/** @covers \Domain\Position\Repositories\PositionRepository::updateApproveRound */
it('tests updateApproveRound method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->create();

    $round = fake()->numberBetween(1, 10);

    $position = $repository->updateApproveRound($position, $round);

    assertSame($round, $position->approve_round);
});
