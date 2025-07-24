<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Application\Repositories;

use App\Enums\LanguageEnum;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Candidate\Enums\SourceEnum;
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

/** @covers \Domain\Application\Repositories\ApplicationRepository::existsDuplicateOnPosition */
it('tests existsDuplicateOnPosition method', function (): void {
    /** @var ApplicationRepositoryInterface $repository */
    $repository = app(ApplicationRepositoryInterface::class);

    $email = fake()->safeEmail;
    $phonePrefix = '+420';
    $phoneNumber = fake()->phoneNumber;

    $position = Position::factory()->create();

    Application::factory()->ofPosition($position)->create(['email' => $email]);
    Application::factory()->ofPosition($position)->create(['phone_prefix' => $phonePrefix, 'phone_number' => $phoneNumber]);

    assertTrue($repository->existsDuplicateOnPosition($position, $email, '+420', fake()->phoneNumber));
    assertTrue($repository->existsDuplicateOnPosition($position, fake()->safeEmail, $phonePrefix, $phoneNumber));
});
