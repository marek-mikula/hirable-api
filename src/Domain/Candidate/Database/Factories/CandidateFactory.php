<?php

declare(strict_types=1);

namespace Domain\Candidate\Database\Factories;

use Database\Factories\Factory;
use Domain\Candidate\Models\Candidate;
use Domain\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Str;

/**
 * @extends BaseFactory<Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $linkedinUsername = Str::lower(fake()->userName());

        return [
            'company_id' => $this->isMaking ? null : Company::factory(),
            'firstname' => fake()->firstName($gender),
            'lastname' => fake()->lastName($gender),
            'email' => fake()->unique()->email(),
            'phone_prefix' => '+420',
            'phone_number' => fake()->phoneNumber,
            'linkedin' => sprintf('https://www.linkedin.com/in/%s/', $linkedinUsername),
        ];
    }
}
