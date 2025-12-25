<?php

declare(strict_types=1);

namespace Domain\Candidate\Jobs;

use App\Jobs\CommonJob;
use Domain\Candidate\UseCases\CreateCandidateFromCvUseCase;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Support\File\Models\File;

final class CreateCandidateFromCvJob extends CommonJob
{
    public function __construct(
        public readonly User $user,
        public readonly File $cv,
        public readonly Position|null $position,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        CreateCandidateFromCvUseCase::make()->handle(
            $this->user,
            $this->cv,
            $this->position
        );
    }
}
