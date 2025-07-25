<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Http\Request\Data\PositionProcessStepData;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class PositionProcessStepStoreUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
    ) {
    }

    public function handle(User $user, PositionProcessStepData $data): PositionProcessStep
    {
        $input = new PositionProcessStepStoreInput(
            company: $user->company,
            step: $data->step,
        );

        return DB::transaction(function () use ($input): PositionProcessStep {
            return $this->positionProcessStepRepository->store($input);
        });
    }
}
