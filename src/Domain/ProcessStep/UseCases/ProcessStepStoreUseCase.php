<?php

declare(strict_types=1);

namespace Domain\ProcessStep\UseCases;

use App\UseCases\UseCase;
use Domain\ProcessStep\Https\Requests\Data\ProcessStepData;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\Inputs\ProcessStepStoreInput;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class ProcessStepStoreUseCase extends UseCase
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
    ) {
    }

    public function handle(User $user, ProcessStepData $data): ProcessStep
    {
        $input = new ProcessStepStoreInput(
            company: $user->company,
            step: $data->step,
        );

        return DB::transaction(function () use ($input): ProcessStep {
            return $this->processStepRepository->store($input);
        }, attempts: 5);
    }
}
