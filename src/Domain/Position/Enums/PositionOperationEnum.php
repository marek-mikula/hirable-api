<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum PositionOperationEnum: string
{
    case SAVE = 'save';
    case SEND_FOR_APPROVAL = 'sendForApproval';
    case OPEN = 'open';
}
