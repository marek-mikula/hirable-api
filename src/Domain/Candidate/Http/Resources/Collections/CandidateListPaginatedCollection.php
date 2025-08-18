<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources\Collections;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\Candidate\Http\Resources\CandidateListResource;

class CandidateListPaginatedCollection extends PaginatedCollection
{
    public $collects = CandidateListResource::class;
}
