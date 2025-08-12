<?php

declare(strict_types=1);

namespace Domain\ProcessStep\UseCases;

use App\UseCases\UseCase;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProcessStepDeleteUseCase extends UseCase
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
    ) {
    }

    public function handle(ProcessStep $processStep): void
    {
        DB::transaction(function () use ($processStep): void {
            $this->processStepRepository->delete($processStep);
        }, attempts: 5);
    }
}
