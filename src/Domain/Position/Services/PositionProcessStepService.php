<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Data\ProcessStepData;
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
     * Gets default steps for position when position
     * is moved to opened state.
     *
     * Mixes fixed and default process steps together
     * either based on default config or position template
     * schema defined by user.
     *
     * @return ProcessStepData[]
     */
    public function getDefaultProcessSteps(Position $position): array
    {
        $result = [];

        $stepsPlacement = $this->processStepConfigService->getStepsPlacement();

        foreach ($this->processStepConfigService->getFixedSteps() as $fixedStep) {
            $result[] = new ProcessStepData(
                step: $fixedStep,
                isFixed: true,
                isRepeatable: false,
            );

            if ($fixedStep !== $stepsPlacement) {
                continue;
            }

            $result = [...$result, ...$this->getConfigurableSteps($position)];
        }

        return $result;
    }

    /**
     * @return ProcessStepData[]
     */
    private function getConfigurableSteps(Position $position): array
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

            $result[] = new ProcessStepData(
                step: $configurableStep,
                isFixed: false,
                isRepeatable: $step->is_repeatable,
            );
        }

        return $result;
    }
}
