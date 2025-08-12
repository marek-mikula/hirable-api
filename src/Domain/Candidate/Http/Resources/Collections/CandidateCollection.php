<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources\Collections;

use Domain\Candidate\Http\Resources\CandidateResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CandidateCollection extends ResourceCollection
{
    public $collects = CandidateResource::class;
}
