<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum ActionStateEnum: string
{
    case CREATED = 'created';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
