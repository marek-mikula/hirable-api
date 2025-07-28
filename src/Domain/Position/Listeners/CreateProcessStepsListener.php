<?php

declare(strict_types=1);

namespace Domain\Position\Listeners;

use App\Listeners\Listener;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Inputs\PositionProcessStepStoreInput;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\Position\Services\PositionProcessStepService;

class CreateProcessStepsListener extends Listener
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
        private readonly PositionProcessStepService $positionProcessStepService,
    ) {
    }

    public function handle(PositionOpenedEvent $event): void
    {
        // do not trigger events, because otherwise
        // it would trigger observer infinite times
        Position::withoutEvents(function () use ($event): void {
            foreach ($this->positionProcessStepService->getDefaultProcessSteps($event->position) as $index => $step) {
                $this->positionProcessStepRepository->store(
                    new PositionProcessStepStoreInput(
                        position: $event->position,
                        step: $step->step,
                        order: $index,
                        isFixed: $step->isFixed,
                        isRepeatable: $step->isRepeatable,
                    )
                );
            }
        });
    }
}
