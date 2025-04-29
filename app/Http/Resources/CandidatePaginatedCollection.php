<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Collections\PaginatedCollection;

class CandidatePaginatedCollection extends PaginatedCollection
{
    public $collects = CandidateResource::class;
}
