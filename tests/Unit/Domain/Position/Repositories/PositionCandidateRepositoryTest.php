<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Position\Repositories;

use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;

use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Position\Repositories\PositionCandidateRepository::store */
it('tests store method', function (): void {
    /** @var PositionCandidateRepositoryInterface $repository */
    $repository = app(PositionCandidateRepositoryInterface::class);
})->todo();

/** @covers \Domain\Position\Repositories\PositionCandidateRepository::setScore */
it('tests setScore method', function (): void {
    /** @var PositionCandidateRepositoryInterface $repository */
    $repository = app(PositionCandidateRepositoryInterface::class);

    $positionCandidate = PositionCandidate::factory()->create([
        'score' => [],
        'total_score' => null,
    ]);

    $score = ['hardSkills' => 90, 'softSkills' => 45];
    $totalScore = 87;

    $positionCandidate = $repository->setScore($positionCandidate, $score, $totalScore);

    assertSame($score, $positionCandidate->score);
    assertSame($totalScore, $positionCandidate->total_score);
});
