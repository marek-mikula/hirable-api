<?php

declare(strict_types=1);

namespace Domain\Candidate\Database\Factories;

use Database\Factories\Factory;
use Domain\Candidate\Models\Candidate;
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
        $linkedinUsername = Str::lower(fake()->userName());

        return [
            'firstname' => fake()->firstName,
            'lastname' => fake()->lastName,
            'email' => fake()->unique()->email(),
            'phone_prefix' => '+420',
            'phone_number' => fake()->phoneNumber,
            'linkedin' => sprintf('https://www.linkedin.com/in/%s/', $linkedinUsername),
        ];
    }
}
