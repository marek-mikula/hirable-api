<?php

declare(strict_types=1);

namespace Domain\Candidate\Policies;

use Domain\Candidate\Models\Candidate;
use Domain\User\Models\User;

class CandidatePolicy
{
    public function show(User $user, Candidate $candidate): bool
    {
        return $user->company_id === $candidate->id;
    }
}
