<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Services;

use App\Services\Service;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\ProcessStep\Enums\StepEnum;

class ProcessStepActionService extends Service
{
    /**
     * @return ActionTypeEnum[]
     */
    public function getAllowedActionsForStep(StepEnum|string $step): array
    {
        return match ($step) {
            StepEnum::OFFER => [
                ActionTypeEnum::COMMUNICATION,
                ActionTypeEnum::OFFER,
            ],
            StepEnum::PLACEMENT => [
                ActionTypeEnum::COMMUNICATION,
                ActionTypeEnum::START_OF_WORK,
            ],
            StepEnum::REJECTED => [
                ActionTypeEnum::COMMUNICATION,
                ActionTypeEnum::REJECTION,
            ],
            default => [
                ActionTypeEnum::INTERVIEW,
                ActionTypeEnum::TASK,
                ActionTypeEnum::ASSESSMENT_CENTER,
                ActionTypeEnum::COMMUNICATION,
                ActionTypeEnum::CUSTOM,
            ],
        };
    }

    /**
     * @return ActionTypeEnum[]
     */
    public function getSingleActions(): array
    {
        return [
            ActionTypeEnum::OFFER,
            ActionTypeEnum::REJECTION,
            ActionTypeEnum::START_OF_WORK,
        ];
    }

    /**
     * @return ActionTypeEnum[]
     */
    public function getTriggerableAction(): array
    {
        return [
            ActionTypeEnum::INTERVIEW,
            ActionTypeEnum::TASK,
            ActionTypeEnum::ASSESSMENT_CENTER,
            ActionTypeEnum::COMMUNICATION,
            ActionTypeEnum::CUSTOM,
        ];
    }
}
