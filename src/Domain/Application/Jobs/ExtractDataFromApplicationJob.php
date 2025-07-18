<?php

declare(strict_types=1);

namespace Domain\Application\Jobs;

use App\Jobs\CommonJob;
use Domain\Application\Models\Application;

class ExtractDataFromApplicationJob extends CommonJob
{
    public function __construct(
        private readonly Application $application,
    ) {
    }

    public function handle(): void
    {
        // todo
    }
}
