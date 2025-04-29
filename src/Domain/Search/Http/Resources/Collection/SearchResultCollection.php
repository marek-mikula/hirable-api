<?php

declare(strict_types=1);

namespace Domain\Search\Http\Resources\Collection;

use Domain\Search\Http\Resources\SearchResultResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchResultCollection extends ResourceCollection
{
    public $collects = SearchResultResource::class;
}
