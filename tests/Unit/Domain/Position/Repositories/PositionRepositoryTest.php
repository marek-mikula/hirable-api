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

/** @covers \Domain\Position\Repositories\PositionRepository::findBy */
it('tests findBy method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $name1 = fake()->unique()->word;
    $name2 = fake()->unique()->word;

    $position1 = Position::factory()->ofName($name1)->create();
    $position2 = Position::factory()->ofName($name2)->create();

    assertTrue($position1->is($repository->findBy(['name' => $name1])));
    assertTrue($position2->is($repository->findBy(['name' => $name2])));
});

/** @covers \Domain\Position\Repositories\PositionRepository::store */
it('tests store method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $company = Company::factory()->create();
    $user = User::factory()->ofCompany($company, RoleEnum::RECRUITER)->create();

    $input = new PositionStoreInput(
        company: $company,
        user: $user,
        name: fake()->jobTitle,
        externName: fake()->jobTitle,
        approveUntil: null,
        approveMessage: fake()->text(500),
        department: fake()->word,
        field: fake()->word,
        jobSeatsNum: fake()->numberBetween(0, 10),
        description: fake()->text(2000),
        address: fake()->words(asText: true),
        salaryFrom: fake()->numberBetween(1, 50000),
        salaryTo: fake()->numberBetween(50000, 100000),
        salaryType: fake()->word,
        salaryFrequency: fake()->word,
        salaryCurrency: fake()->word,
        salaryVar: fake()->word,
        minEducationLevel: fake()->word,
        seniority: fake()->words(fake()->numberBetween(0, 5)),
        experience: fake()->numberBetween(1, 5),
        hardSkills: fake()->text(2000),
        organisationSkills: fake()->numberBetween(0, 100),
        teamSkills: fake()->numberBetween(0, 100),
        timeManagement: fake()->numberBetween(0, 100),
        communicationSkills: fake()->numberBetween(0, 100),
        leadership: fake()->numberBetween(0, 100),
        note: fake()->text(2000),
        workloads: fake()->words(fake()->numberBetween(0, 5)),
        employmentRelationships: fake()->words(fake()->numberBetween(0, 5)),
        employmentForms: fake()->words(fake()->numberBetween(0, 5)),
        benefits: fake()->words(fake()->numberBetween(0, 5)),
        languageRequirements: fake()->words(fake()->numberBetween(0, 5)),
        hardSkillsWeight: fake()->numberBetween(0, 100),
        softSkillsWeight: fake()->numberBetween(0, 100),
        languageSkillsWeight: fake()->numberBetween(0, 100),
        shareSalary: fake()->boolean,
        shareContact: fake()->boolean,
    );

    $position = $repository->store($input);

    assertSame($input->company->id, $position->company_id);
    assertSame($input->user->id, $position->user_id);
    assertSame($input->name, $position->name);
    assertSame($input->externName, $position->extern_name);
    assertSame($input->approveUntil, $position->approve_until);
    assertSame($input->approveMessage, $position->approve_message);
    assertSame($input->department, $position->department);
    assertSame($input->field, $position->field);
    assertSame($input->jobSeatsNum, $position->job_seats_num);
    assertSame($input->description, $position->description);
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
    assertSame($input->shareSalary, $position->share_salary);
    assertSame($input->shareContact, $position->share_contact);

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
        name: fake()->jobTitle,
        externName: fake()->jobTitle,
        approveUntil: null,
        approveMessage: fake()->text(500),
        department: fake()->word,
        field: fake()->word,
        jobSeatsNum: fake()->numberBetween(0, 10),
        description: fake()->text(2000),
        address: fake()->words(asText: true),
        salaryFrom: fake()->numberBetween(1, 50000),
        salaryTo: fake()->numberBetween(50000, 100000),
        salaryType: fake()->word,
        salaryFrequency: fake()->word,
        salaryCurrency: fake()->word,
        salaryVar: fake()->word,
        minEducationLevel: fake()->word,
        seniority: fake()->words(fake()->numberBetween(0, 5)),
        experience: fake()->numberBetween(1, 5),
        hardSkills: fake()->text(2000),
        organisationSkills: fake()->numberBetween(0, 100),
        teamSkills: fake()->numberBetween(0, 100),
        timeManagement: fake()->numberBetween(0, 100),
        communicationSkills: fake()->numberBetween(0, 100),
        leadership: fake()->numberBetween(0, 100),
        note: fake()->text(2000),
        workloads: fake()->words(fake()->numberBetween(0, 5)),
        employmentRelationships: fake()->words(fake()->numberBetween(0, 5)),
        employmentForms: fake()->words(fake()->numberBetween(0, 5)),
        benefits: fake()->words(fake()->numberBetween(0, 5)),
        languageRequirements: fake()->words(fake()->numberBetween(0, 5)),
        hardSkillsWeight: fake()->numberBetween(0, 100),
        softSkillsWeight: fake()->numberBetween(0, 100),
        languageSkillsWeight: fake()->numberBetween(0, 100),
        shareSalary: fake()->boolean,
        shareContact: fake()->boolean,
    );

    $position = $repository->update($position, $input);

    assertSame($input->name, $position->name);
    assertSame($input->externName, $position->extern_name);
    assertSame($input->approveUntil, $position->approve_until);
    assertSame($input->approveMessage, $position->approve_message);
    assertSame($input->department, $position->department);
    assertSame($input->field, $position->field);
    assertSame($input->jobSeatsNum, $position->job_seats_num);
    assertSame($input->description, $position->description);
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
    assertSame($input->shareSalary, $position->share_salary);
    assertSame($input->shareContact, $position->share_contact);
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

/** @covers \Domain\Position\Repositories\PositionRepository::setTokens */
it('tests setTokens method', function (): void {
    /** @var PositionRepositoryInterface $repository */
    $repository = app(PositionRepositoryInterface::class);

    $position = Position::factory()->create();

    $commonToken = fake()->word;
    $internToken = fake()->word;
    $referralToken = fake()->word;

    $position = $repository->setTokens(
        position: $position,
        commonToken: $commonToken,
        internToken: $internToken,
        referralToken: $referralToken,
    );

    assertSame($commonToken, $position->common_token);
    assertSame($internToken, $position->intern_token);
    assertSame($referralToken, $position->referral_token);
});
