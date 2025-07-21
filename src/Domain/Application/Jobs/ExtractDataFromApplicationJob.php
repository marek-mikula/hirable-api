<?php

declare(strict_types=1);

namespace Domain\Application\Jobs;

use App\Jobs\CommonJob;
use Domain\Application\Models\Application;
use Domain\Application\UseCases\ExtractDataFromApplicationUseCase;

class ExtractDataFromApplicationJob extends CommonJob
{
    public function __construct(
        private readonly Application $application,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $application = ExtractDataFromApplicationUseCase::make()->handle($this->application);
        ScoreApplicationJob::dispatch($application);
    }
}
