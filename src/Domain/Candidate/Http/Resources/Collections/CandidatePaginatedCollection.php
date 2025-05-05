<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\Candidate\Http\Resources\CandidateResource;

class CandidatePaginatedCollection extends PaginatedCollection
{
    public $collects = CandidateResource::class;
}
