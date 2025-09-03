<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionOperationEnum: string
{
    case SAVE = 'save';
    case FINISH = 'finish';
    case CANCEL = 'cancel';
}
