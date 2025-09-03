<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionTaskResultEnum: string
{
    case NOT_PARTICIPATED = 'notParticipated';
    case PASSED_EXCEPTIONALLY = 'passedExceptionally';
    case PASSED = 'passed';
    case PASSED_WITH_EXCEPTIONS = 'passedWithExceptions';
    case FAILED = 'failed';
    case INVALID = 'invalid';
    case OTHER = 'other';
}
