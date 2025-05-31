<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use App\Exceptions\NeedsRelationshipException;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $resource
 */
trait ChecksRelations
{
    /**
     * @param  string[]  $relations
     * @param  class-string<Model>  $model
     */
    protected function checkLoadedRelations(array $relations, string $model, ?Model $resource = null): void
    {
        $resource = $resource ?? $this->resource;

        foreach ($relations as $relation) {
            throw_unless($resource->relationLoaded($relation), new NeedsRelationshipException(static::class, $model, $relation));
        }
    }
}
