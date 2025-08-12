<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Resources\Collections;

use Domain\ProcessStep\Https\Resources\ProcessStepResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProcessStepCollection extends ResourceCollection
{
    public $collects = ProcessStepResource::class;
}
