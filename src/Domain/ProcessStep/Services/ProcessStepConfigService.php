<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Services;

use App\Services\Service;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Support\Collection;

class ProcessStepConfigService extends Service
{
    /**
     * @return Collection<string,array>
     */
    public function getFixedSteps(): Collection
    {
        return collect((array) config('process_step.fixed_steps'));
    }

    public function getStepsPlacement(): StepEnum
    {
        $stepsPlacement = StepEnum::from((string) config('process_step.steps_placement'));

        throw_if(
            !$this->getFixedSteps()->has($stepsPlacement->value),
            new \InvalidArgumentException('Steps placement must be included in fixed states.')
        );

        return $stepsPlacement;
    }
}
