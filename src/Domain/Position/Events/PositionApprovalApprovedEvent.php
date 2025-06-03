<?php

declare(strict_types=1);

namespace Domain\Position\Events;

use App\Events\Event;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;

class PositionApprovalApprovedEvent extends Event
{
    public function __construct(
        public Position $position,
        public PositionApproval $approval,
        public User|CompanyContact $approvedBy,
    ) {
    }
}
