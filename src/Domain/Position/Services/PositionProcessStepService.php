<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Data\PositionProcessStepData;
use Domain\Position\Models\Position;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Domain\ProcessStep\Services\ProcessStepConfigService;

class PositionProcessStepService
{
    public function __construct(
        private readonly ProcessStepRepositoryInterface $processStepRepository,
        private readonly ProcessStepConfigService $processStepConfigService,
        private readonly PositionConfigService $positionConfigService,
    ) {
    }

    /**
     * Gets default position process steps, which are
     * created when position is moved into opened state.
     *
     * Mixes fixed and default process steps together
     * either based on default config or position template
     * schema defined by user.
     *
     * @return PositionProcessStepData[]
     */
    public function getDefaultPositionProcessSteps(Position $position): array
    {
        $result = [];

        $stepsPlacement = $this->processStepConfigService->getStepsPlacement();

        foreach ($this->processStepConfigService->getFixedSteps() as $fixedStep) {
            $result[] = new PositionProcessStepData(
                step: $fixedStep,
                isFixed: true,
                isRepeatable: false,
            );

            if ($fixedStep !== $stepsPlacement) {
                continue;
            }

            $result = [...$result, ...$this->getConfigurablePositionProcessSteps($position)];
        }

        return $result;
    }

    /**
     * @return PositionProcessStepData[]
     */
    private function getConfigurablePositionProcessSteps(Position $position): array
    {
        // todo allow users to configure their default process steps via position template

        $result = [];

        $steps = $this->processStepRepository->getByCompany($position->company, includeCommon: true);

        foreach ($this->positionConfigService->getDefaultConfigurableProcessSteps() as $configurableStep) {
            /** @var ProcessStep|null $step */
            $step = $steps->first(fn (ProcessStep $processStep) => $processStep->step === $configurableStep);

            if (!$step) {
                continue;
            }

            $result[] = new PositionProcessStepData(
                step: $configurableStep,
                isFixed: false,
                isRepeatable: $step->is_repeatable,
            );
        }

        return $result;
    }
}
