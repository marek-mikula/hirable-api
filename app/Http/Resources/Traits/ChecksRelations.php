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
    protected function checkLoadedRelations(array $relations, string $model): void
    {
        foreach ($relations as $relation) {
            throw_unless($this->resource->relationLoaded($relation), new NeedsRelationshipException(static::class, $model, $relation));
        }
    }
}
