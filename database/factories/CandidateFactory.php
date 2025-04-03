<?php

namespace Database\Factories;

use App\Models\Candidate;
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
            'phone_prefix' => null,
            'phone' => null,
            'linkedin' => "https://www.linkedin.com/in/{$linkedinUsername}/",
        ];
    }
}
