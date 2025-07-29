<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
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
        if (!$processStep->is_repeatable && $this->positionProcessStepRepository->hasStep($position, $processStep->step)) {
            throw new HttpException(responseCode: ResponseCodeEnum::STEP_EXISTS);
        }

        $order = $this->positionProcessStepRepository->getMaxOrder($position);

        return DB::transaction(function () use (
            $position,
            $processStep,
            $order,
        ): PositionProcessStep {
            return $this->positionProcessStepRepository->store(
                new PositionProcessStepStoreInput(
                    position: $position,
                    step: $processStep->step,
                    order: $order + 1,
                    isFixed: false,
                    isRepeatable: $processStep->is_repeatable,
                )
            );
        }, attempts: 5);
    }
}
