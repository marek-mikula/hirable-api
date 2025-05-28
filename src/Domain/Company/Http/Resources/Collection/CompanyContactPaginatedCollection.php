<?php

declare(strict_types=1);

namespace Domain\Company\Http\Resources\Collection;

use App\Http\Resources\Collections\PaginatedCollection;
use Domain\Company\Http\Resources\CompanyContactResource;

class CompanyContactPaginatedCollection extends PaginatedCollection
{
    public $collects = CompanyContactResource::class;
}
