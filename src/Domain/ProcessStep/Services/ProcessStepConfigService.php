<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Services;

use App\Services\Service;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Illuminate\Support\Collection;

class ProcessStepConfigService extends Service
{
    /**
     * @return Collection<ProcessStepEnum>
     */
    public function getFixedSteps(): Collection
    {
        return collect((array) config('process_step.fixed_steps'))
            ->map(fn (string $step) => ProcessStepEnum::from($step));
    }

    public function getStepsPlacement(): ProcessStepEnum
    {
        $stepsPlacement = ProcessStepEnum::from((string) config('process_step.steps_placement'));

        throw_if(
            $this->getFixedSteps()->contains($stepsPlacement->value),
            new \InvalidArgumentException('Steps placement must be included in fixed states.')
        );

        return $stepsPlacement;
    }
}
