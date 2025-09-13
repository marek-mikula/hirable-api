<?php

declare(strict_types=1);

namespace Domain\ProcessStep\UseCases;

use App\UseCases\UseCase;
use Domain\ProcessStep\Https\Requests\Data\ProcessStepData;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Input\ProcessStepUpdateInput;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProcessStepUpdateUseCase extends UseCase
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
    ) {
    }

    public function handle(ProcessStep $processStep, ProcessStepData $data): ProcessStep
    {
        $input = new ProcessStepUpdateInput(
            step: $data->step,
            isRepeatable: $data->isRepeatable,
            triggersAction: $data->triggersAction,
        );

        return DB::transaction(fn (): ProcessStep => $this->processStepRepository->update($processStep, $input), attempts: 5);
    }
}
