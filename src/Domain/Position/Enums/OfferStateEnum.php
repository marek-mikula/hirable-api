<?php

declare(strict_types=1);

namespace Domain\Position\Enums;

enum OfferStateEnum: string
{
    case WAITING = 'waiting';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
