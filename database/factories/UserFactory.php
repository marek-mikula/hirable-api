<?php

namespace Database\Factories;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Models\Company;
use App\Models\User;
use Domain\Company\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'company_id' => $this->isMaking ? null : Company::factory(),
            'company_role' => RoleEnum::ADMIN,
            'language' => LanguageEnum::EN,
            'timezone' => null,
            'firstname' => fake()->firstName($gender),
            'lastname' => fake()->lastName($gender),
            'prefix' => null,
            'postfix' => null,
            'phone' => null,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'Admin.123',
            'agreement_ip' => '127.0.0.1',
            'agreement_accepted_at' => now(),
            'remember_token' => null,
            'notification_crucial_mail' => true,
            'notification_crucial_app' => true,
            'notification_technical_mail' => true,
            'notification_technical_app' => true,
            'notification_marketing_mail' => true,
            'notification_marketing_app' => true,
            'notification_application_mail' => true,
            'notification_application_app' => true,
        ];
    }

    public function ofCrucialNotifications(bool $mail = true, bool $app = true): static
    {
        return $this->state(fn (array $attributes) => [
            'notification_crucial_mail' => $mail,
            'notification_crucial_app' => $app,
        ]);
    }

    public function ofTechnicalNotifications(bool $mail = true, bool $app = true): static
    {
        return $this->state(fn (array $attributes) => [
            'notification_technical_mail' => $mail,
            'notification_technical_app' => $app,
        ]);
    }

    public function ofMarketingNotifications(bool $mail = true, bool $app = true): static
    {
        return $this->state(fn (array $attributes) => [
            'notification_marketing_mail' => $mail,
            'notification_marketing_app' => $app,
        ]);
    }

    public function ofApplicationNotifications(bool $mail = true, bool $app = true): static
    {
        return $this->state(fn (array $attributes) => [
            'notification_application_mail' => $mail,
            'notification_application_app' => $app,
        ]);
    }

    public function emailNotVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function emailVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    public function ofEmail(string $email): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => $email,
        ]);
    }

    public function ofPassword(string $password): static
    {
        return $this->state(fn (array $attributes) => [
            'password' => $password,
        ]);
    }

    public function ofLanguage(LanguageEnum $language): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => $language,
        ]);
    }

    public function ofTimezone(?TimezoneEnum $timezone): static
    {
        return $this->state(fn (array $attributes) => [
            'timezone' => $timezone,
        ]);
    }

    public function ofCompany(Company $company, RoleEnum $role): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ])->ofCompanyRole($role);
    }

    public function ofCompanyRole(RoleEnum $role): static
    {
        return $this->state(fn (array $attributes) => [
            'company_role' => $role,
        ]);
    }
}
