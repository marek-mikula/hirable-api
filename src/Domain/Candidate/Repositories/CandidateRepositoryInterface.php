<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories;

use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Candidate\Repositories\Input\CandidateUpdateInput;
use Domain\Company\Models\Company;

interface CandidateRepositoryInterface
{
    public function store(CandidateStoreInput $input): Candidate;

    public function update(Candidate $candidate, CandidateUpdateInput $input): Candidate;

    public function findDuplicateInCompany(Company $company, string $email, string $phonePrefix, string $phoneNumber): ?Candidate;
}
