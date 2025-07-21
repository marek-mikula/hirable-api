<?php

declare(strict_types=1);

namespace Domain\Application\Database\Factories;

use App\Enums\LanguageEnum;
use Database\Factories\Factory;
use Domain\Application\Models\Application;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'uuid' => fake()->uuid,
            'position_id' => $this->isMaking ? null : Position::factory()->ofState(PositionStateEnum::OPENED),
            'candidate_id' => null,
            'processed' => false,
            'language' => LanguageEnum::EN,
            'gender' => GenderEnum::MALE,
            'source' => SourceEnum::POSITION,
            'firstname' => fake()->firstName($gender),
            'lastname' => fake()->lastName($gender),
            'email' => fake()->unique()->safeEmail,
            'phone_prefix' => '+420',
            'phone_number' => fake()->phoneNumber,
            'linkedin' => null,
            'instagram' => null,
            'github' => null,
            'portfolio' => null,
            'birth_date' => fake()->date,
            'experience' => [],
            'score' => [],
            'total_score' => null,
        ];
    }

    public function ofProcessed(bool $processed): static
    {
        return $this->state(fn (array $attributes) => [
            'processed' => $processed,
        ]);
    }
}
