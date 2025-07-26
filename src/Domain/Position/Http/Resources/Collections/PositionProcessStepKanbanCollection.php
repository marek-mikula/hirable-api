<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources\Collections;

use Domain\Position\Http\Resources\PositionProcessStepKanbanResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionProcessStepKanbanCollection extends ResourceCollection
{
    public $collects = PositionProcessStepKanbanResource::class;
}
