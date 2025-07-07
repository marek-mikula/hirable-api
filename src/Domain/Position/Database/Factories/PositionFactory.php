<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Carbon\Carbon;
use Database\Factories\Factory;
use Domain\Company\Models\Company;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseFactory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $salarySpan = fake()->boolean;
        $isTechnical = fake()->boolean;

        $currencies = [
            'CZK',
            'USD',
            'EUR',
        ];

        $salaryFrequencies = [
            'hourly',
            'daily',
            'monthly',
            'quarterly',
            'yearly',
        ];

        $salaryTypes = [
            'gross',
            'net',
        ];

        $salary = fake()->numberBetween(5000, 150000);

        return [
            'company_id' => $this->isMaking ? null : Company::factory(),
            'user_id' => $this->isMaking ? null : User::factory(),
            'state' => PositionStateEnum::OPENED,
            'approve_until' => null,
            'approve_message' => null,
            'approve_round' => null,
            'name' => fake()->jobTitle,
            'department' => str(fake()->word)->transliterate()->lower()->toString(),
            'field' => str(fake()->word)->transliterate()->lower()->toString(),
            'workloads' => [
                str(fake()->word)->transliterate()->lower()->toString()
            ],
            'employment_relationships' => [
                str(fake()->word)->transliterate()->lower()->toString()
            ],
            'employment_forms' => [
                str(fake()->word)->transliterate()->lower()->toString()
            ],
            'job_seats_num' => fake()->numberBetween(1, 100),
            'description' => fake()->text(2000),
            'is_technical' => $isTechnical,
            'address' => fake()->address,
            'salary_from' => $salary,
            'salary_to' => $salarySpan ? ($salary + fake()->numberBetween(10000, 50000)) : null,
            'salary_type' => fake()->randomElement($salaryTypes),
            'salary_frequency' => fake()->randomElement($salaryFrequencies),
            'salary_currency' => fake()->randomElement($currencies),
            'salary_var' => fake()->words(asText: true),
            'benefits' => [],
            'min_education_level' => str(fake()->word)->transliterate()->lower()->toString(),
            'seniority' => $isTechnical ? str(fake()->word)->transliterate()->lower()->toString() : null,
            'experience' => fake()->numberBetween(0, 10),
            'hard_skills' => null,
            'organisation_skills' => fake()->numberBetween(0, 10),
            'team_skills' => fake()->numberBetween(0, 10),
            'time_management' => fake()->numberBetween(0, 10),
            'communication_skills' => fake()->numberBetween(0, 10),
            'leadership' => fake()->numberBetween(0, 10),
            'language_requirements' => [],
            'note' => fake()->boolean ? fake()->text(2000) : null,
            'hard_skills_weight' => fake()->numberBetween(0, 10),
            'soft_skills_weight' => fake()->numberBetween(0, 10),
            'language_skills_weight' => fake()->numberBetween(0, 10),
        ];
    }

    public function ofCompany(Company|int $company): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => is_int($company) ? $company : $company->id,
        ]);
    }

    public function ofUser(User|int $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => is_int($user) ? $user : $user->id,
        ]);
    }

    public function ofState(PositionStateEnum $state): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => $state,
        ]);
    }

    public function ofApproveUntil(?Carbon $timestamp): static
    {
        return $this->state(fn (array $attributes) => [
            'approve_until' => $timestamp,
        ]);
    }

    public function ofApproveMessage(string $approveMessage): static
    {
        return $this->state(fn (array $attributes) => [
            'approve_message' => $approveMessage,
        ]);
    }

    public function withModel(Model $model, PositionRoleEnum $role): static
    {
        return $this->afterCreating(function (Position $position) use ($model, $role): void {
            ModelHasPosition::factory()
                ->ofPosition($position)
                ->ofModel($model)
                ->ofRole($role)
                ->create();
        });
    }

    public function withApproval(Model $model, PositionApprovalStateEnum $state): static
    {
        return $this->afterCreating(function (Position $position) use ($model, $state): void {
            $modelHasPosition = ModelHasPosition::factory()
                ->ofPosition($position)
                ->ofModel($model)
                ->ofRole(PositionRoleEnum::APPROVER)
                ->create();

            PositionApproval::factory()
                ->ofModelHasPosition($modelHasPosition)
                ->ofState($state)
                ->create();
        });
    }
}
