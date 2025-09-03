<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Input\PositionProcessStepStoreInput;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PositionProcessStepStoreUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
        private readonly ProcessStepRepositoryInterface $processStepRepository
    ) {
    }

    public function handle(Position $position, int $processStepId): PositionProcessStep
    {
        $processStep = $this->processStepRepository->find($processStepId);

        abort_if(empty($processStep), code: 400);

        // position already has selected
        // step and the step is not repeatable
        if (!$processStep->is_repeatable && $this->positionProcessStepRepository->positionHasStep($position, $processStep->step)) {
            throw new HttpException(responseCode: ResponseCodeEnum::STEP_EXISTS);
        }

        $nextOrder = $this->positionProcessStepRepository->getNextOrderNum($position);

        return DB::transaction(function () use (
            $position,
            $processStep,
            $nextOrder,
        ): PositionProcessStep {
            return $this->positionProcessStepRepository->store(
                new PositionProcessStepStoreInput(
                    position: $position,
                    step: $processStep->step,
                    label: null,
                    order: $nextOrder,
                    isFixed: false,
                    isRepeatable: $processStep->is_repeatable,
                    triggersAction: $processStep->triggers_action,
                )
            );
        }, attempts: 5);
    }
}
