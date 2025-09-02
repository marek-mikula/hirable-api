<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use App\Exceptions\NeedsRelationshipException;
use App\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property Model $resource
 * @mixin Resource
 */
trait ChecksRelations
{
    /**
     * @param  string[]|string  $relations
     */
    protected function checkLoadedRelations(array|string $relations, ?Model $resource = null): void
    {
        $resource = $resource ?? $this->resource;

        foreach (Arr::wrap($relations) as $relation) {
            throw_unless($resource->relationLoaded($relation), new NeedsRelationshipException(static::class, $resource::class, $relation));
        }
    }
}
