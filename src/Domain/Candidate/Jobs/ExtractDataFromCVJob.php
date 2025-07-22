<?php

declare(strict_types=1);

namespace Domain\Candidate\Jobs;

use App\Jobs\CommonJob;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\UseCases\ExtractDataFromCVUseCase;

class ExtractDataFromCVJob extends CommonJob
{
    public function __construct(
        private readonly Candidate $candidate,
    ) {
        parent::__construct();
    }

    public function handle(ExtractDataFromCVUseCase $useCase): void
    {
        $useCase->handle($this->candidate);
    }
}
