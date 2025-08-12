<?php

declare(strict_types=1);

namespace Domain\Position\Http\Resources\Collections;

use Domain\Position\Http\Resources\KanbanStepResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class KanbanStepCollection extends ResourceCollection
{
    public $collects = KanbanStepResource::class;
}
