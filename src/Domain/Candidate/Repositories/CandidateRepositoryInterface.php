<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories;

use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;

interface CandidateRepositoryInterface
{
    public function store(CandidateStoreInput $input): Candidate;

    public function update(Candidate $candidate, CandidateUpdateInput $input): Candidate;
}
