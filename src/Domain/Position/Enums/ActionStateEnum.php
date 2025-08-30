<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionStateEnum: string
{
    // action was created in the system
    case CREATED = 'created';

    // action was sent to the candidate
    case SENT = 'sent';

    // action took place
    case TOOK_PLACE = 'took_place';

    // candidate not showed to the action
    case NOT_SHOWED = 'not_showed';

    // candidate passed the action
    case PASSED = 'passed';

    // candidate failed the action
    case FAILED = 'failed';

    // candidate accepted the action
    case ACCEPTED = 'accepted';

    // candidate rejected the action
    case REJECTED = 'rejected';

    // user or candidate canceled the action
    case CANCELED = 'canceled';

    /**
     * @return ActionStateEnum[]
     */
    public function getNextStatesByType(ActionTypeEnum $type): array
    {
        return match ($type) {
            ActionTypeEnum::INTERVIEW, ActionTypeEnum::ASSESSMENT_CENTER => match ($this) {
                ActionStateEnum::CREATED => [
                    ActionStateEnum::SENT,
                    ActionStateEnum::TOOK_PLACE,
                    ActionStateEnum::NOT_SHOWED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::SENT => [
                    ActionStateEnum::TOOK_PLACE,
                    ActionStateEnum::NOT_SHOWED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::TOOK_PLACE => [
                    ActionStateEnum::NOT_SHOWED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::NOT_SHOWED => [
                    ActionStateEnum::TOOK_PLACE,
                    ActionStateEnum::CANCELED,
                ],
                default => [],
            },
            ActionTypeEnum::TEST, ActionTypeEnum::TASK => match ($this) {
                ActionStateEnum::CREATED => [
                    ActionStateEnum::SENT,
                    ActionStateEnum::PASSED,
                    ActionStateEnum::FAILED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::SENT => [
                    ActionStateEnum::PASSED,
                    ActionStateEnum::FAILED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::PASSED => [
                    ActionStateEnum::FAILED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::FAILED => [
                    ActionStateEnum::PASSED,
                    ActionStateEnum::CANCELED,
                ],
                default => [],
            },
            ActionTypeEnum::OFFER => match ($this) {
                ActionStateEnum::CREATED => [
                    ActionStateEnum::SENT,
                    ActionStateEnum::ACCEPTED,
                    ActionStateEnum::REJECTED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::SENT => [
                    ActionStateEnum::ACCEPTED,
                    ActionStateEnum::REJECTED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::ACCEPTED => [
                    ActionStateEnum::REJECTED,
                    ActionStateEnum::CANCELED,
                ],
                ActionStateEnum::REJECTED => [
                    ActionStateEnum::ACCEPTED,
                    ActionStateEnum::CANCELED,
                ],
                default => [],
            },
            ActionTypeEnum::COMMUNICATION,
            ActionTypeEnum::REJECTION,
            ActionTypeEnum::CUSTOM => match ($this) {
                ActionStateEnum::CREATED => [
                    ActionStateEnum::SENT
                ],
                default => [],
            },
            ActionTypeEnum::REFUSAL => [],
        };
    }
}
