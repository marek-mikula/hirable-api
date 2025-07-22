<?php

declare(strict_types=1);

namespace Domain\Application\Jobs;

use App\Jobs\CommonJob;
use Domain\Application\Models\Application;
use Domain\Application\UseCases\SetApplicationProcessedUseCase;

class SetApplicationProcessedJob extends CommonJob
{
    public function __construct(
        private readonly Application $application,
    ) {
        parent::__construct();
    }

    public function handle(SetApplicationProcessedUseCase $useCase): void
    {
        $useCase->handle($this->application);
    }
}
