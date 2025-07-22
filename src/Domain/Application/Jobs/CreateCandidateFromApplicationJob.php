<?php

declare(strict_types=1);

namespace Domain\Application\Jobs;

use App\Jobs\CommonJob;
use Domain\Application\Models\Application;
use Domain\Application\UseCases\CreateCandidateFromApplicationUseCase;

class CreateCandidateFromApplicationJob extends CommonJob
{
    public function __construct(
        private readonly Application $application,
    ) {
        parent::__construct();
    }

    public function handle(CreateCandidateFromApplicationUseCase $useCase): void
    {
        $useCase->handle($this->application);
    }
}
