<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources\Collections;

use Domain\Position\Http\Resources\PositionApprovalResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionApprovalCollection extends ResourceCollection
{
    public $collects = PositionApprovalResource::class;
}
