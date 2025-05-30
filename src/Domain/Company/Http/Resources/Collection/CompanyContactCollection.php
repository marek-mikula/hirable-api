<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources\Collection;

use Domain\Company\Http\Resources\CompanyContactResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CompanyContactCollection extends ResourceCollection
{
    public $collects = CompanyContactResource::class;
}
