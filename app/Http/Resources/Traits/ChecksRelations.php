<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use App\Exceptions\NeedsCountException;
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
        $resource ??= $this->resource;

        foreach (Arr::wrap($relations) as $relation) {
            throw_unless($resource->relationLoaded($relation), new NeedsRelationshipException(static::class, $resource::class, $relation));
        }
    }

    /**
     * @param  string[]|string  $counts
     */
    protected function checkLoadedCounts(array|string $counts): void
    {
        foreach (Arr::wrap($counts) as $count) {
            throw_unless($this->whenCounted($count, true, false) === true, new NeedsCountException(static::class, $this->resource::class, $count));
        }
    }
}
