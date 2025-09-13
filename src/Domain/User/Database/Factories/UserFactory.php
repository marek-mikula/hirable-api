<?php

declare(strict_types=1);

namespace Domain\User\Database\Factories;

use App\Enums\LanguageEnum;
use Database\Factories\Factory;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\User\Models\User;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $firstname = fake()->firstName($gender);
        $lastname = fake()->lastName($gender);
        $email = sprintf(
            '%s.%s%d@%s',
            Str::lower($firstname),
            Str::lower($lastname),
            fake()->unique()->numerify(),
            fake()->safeEmailDomain,
        );

        return [
            'company_id' => $this->isMaking ? null : Company::factory(),
            'company_role' => RoleEnum::ADMIN,
            'company_owner' => false,
            'language' => LanguageEnum::EN,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'prefix' => null,
            'postfix' => null,
            'phone' => null,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => 'Admin.123',
            'agreement_ip' => '127.0.0.1',
            'agreement_accepted_at' => now(),
            'remember_token' => null,
        ];
    }

    public function emailNotVerified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function emailVerified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => now(),
        ]);
    }

    public function ofEmail(string $email): static
    {
        return $this->state(fn (array $attributes): array => [
            'email' => $email,
        ]);
    }

    public function ofPassword(string $password): static
    {
        return $this->state(fn (array $attributes): array => [
            'password' => $password,
        ]);
    }

    public function ofLanguage(LanguageEnum $language): static
    {
        return $this->state(fn (array $attributes): array => [
            'language' => $language,
        ]);
    }

    public function ofCompany(Company $company, RoleEnum $role): static
    {
        return $this->state(fn (array $attributes): array => [
            'company_id' => $company->id,
        ])->ofCompanyRole($role);
    }

    public function ofCompanyRole(RoleEnum $role): static
    {
        return $this->state(fn (array $attributes): array => [
            'company_role' => $role,
        ]);
    }

    public function companyOwner(bool $state = true): static
    {
        return $this->state(fn (array $attributes): array => [
            'company_owner' => $state,
        ]);
    }
}
