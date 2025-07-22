<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Application\Repositories;

use App\Enums\LanguageEnum;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Domain\Application\Repositories\ApplicationRepository::store */
it('tests store method', function (): void {
    /** @var ApplicationRepositoryInterface $repository */
    $repository = app(ApplicationRepositoryInterface::class);

    $position = Position::factory()->create();

    $input = new ApplicationStoreInput(
        position: $position,
        language: fake()->randomElement(LanguageEnum::cases()),
        source: fake()->randomElement(SourceEnum::cases()),
        firstname: fake()->firstName,
        lastname: fake()->lastName,
        email: fake()->unique()->safeEmail,
        phonePrefix: '+420',
        phoneNumber: fake()->phoneNumber,
        linkedin: fake()->url,
    );

    $application = $repository->store($input);

    assertSame($input->position->id, $application->position->id);
    assertSame($input->language, $application->language);
    assertSame($input->source, $application->source);
    assertSame($input->firstname, $application->firstname);
    assertSame($input->lastname, $application->lastname);
    assertSame($input->email, $application->email);
    assertSame($input->phonePrefix, $application->phone_prefix);
    assertSame($input->phoneNumber, $application->phone_number);
    assertSame($input->linkedin, $application->linkedin);

    assertTrue($application->relationLoaded('position'));
});

/** @covers \Domain\Application\Repositories\ApplicationRepository::setProcessed */
it('tests setProcessed method', function (): void {
    /** @var ApplicationRepositoryInterface $repository */
    $repository = app(ApplicationRepositoryInterface::class);

    $application = Application::factory()->ofProcessed(false)->create();

    $application = $repository->setProcessed($application);

    assertTrue($application->processed);
});

/** @covers \Domain\Application\Repositories\ApplicationRepository::setScore */
it('tests setScore method', function (): void {
    /** @var ApplicationRepositoryInterface $repository */
    $repository = app(ApplicationRepositoryInterface::class);

    $application = Application::factory()->create([
        'score' => [],
        'total_score' => null,
    ]);

    $score = ['hardSkills' => 90, 'softSkills' => 45];
    $totalScore = 87;

    $application = $repository->setScore($application, $score, $totalScore);

    assertSame($score, $application->score);
    assertSame($totalScore, $application->total_score);
});

/** @covers \Domain\Application\Repositories\ApplicationRepository::setCandidate */
it('tests setCandidate method', function (): void {
    /** @var ApplicationRepositoryInterface $repository */
    $repository = app(ApplicationRepositoryInterface::class);

    $candidate = Candidate::factory()->create();
    $application = Application::factory()->create();

    $application = $repository->setCandidate($application, $candidate);

    assertSame($candidate->id, $application->candidate_id);
    assertTrue($application->relationLoaded('candidate'));
});
