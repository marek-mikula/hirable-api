<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\User\Models\User;

readonly class PositionCandidateEvaluationRequestData
{
    public function __construct(
        public User $user,
        public ?Carbon $fillUntil,
    ) {
    }
}
