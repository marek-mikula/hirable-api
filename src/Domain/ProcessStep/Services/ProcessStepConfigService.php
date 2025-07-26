<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Services;

use App\Services\Service;
use Domain\ProcessStep\Data\DefaultStepData;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Illuminate\Support\Arr;

class ProcessStepConfigService extends Service
{
    /**
     * @return DefaultStepData[]
     */
    public function getDefaultSteps(): array
    {
        $steps = (array) config('process_step.default_steps');

        $result = [];

        foreach ($steps as $key => $value) {
            // step has no additional attributes
            if (is_numeric($key)) {
                $result[] = new DefaultStepData(step: ProcessStepEnum::from((string) $value));

                continue;
            }

            throw_if(!is_array($value), new \InvalidArgumentException(sprintf('Invalid step value encountered for step %s.', $key)));

            $round = Arr::get($value, 'round');

            $result[] = new DefaultStepData(
                step: ProcessStepEnum::from((string) $key),
                round: empty($round) ? null : (int) $round,
            );
        }

        return $result;
    }
}
