<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use Domain\Application\Models\Application;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;

interface ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application;

    public function setProcessed(Application $application): Application;

    public function setScore(Application $application, array $score, int $totalScore): Application;

    public function setCandidate(Application $application, Candidate $candidate): Application;

    public function existsDuplicateOnPosition(Position $position, string $email, string $phonePrefix, string $phoneNumber): bool;
}
