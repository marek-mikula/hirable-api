<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Resource extends JsonResource
{
    use ChecksRelations;
}
