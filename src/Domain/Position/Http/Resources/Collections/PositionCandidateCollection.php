<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources\Collections;

use Domain\Position\Http\Resources\PositionCandidateResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionCandidateCollection extends ResourceCollection
{
    public $collects = PositionCandidateResource::class;
}
