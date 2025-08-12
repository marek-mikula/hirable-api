<?php

declare(strict_types=1);

namespace Support\File\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Support\File\Models\File;

/**
 * @mixin Model
 *
 * @property-read Collection<File> $files
 * @property-read int|null $files_count
 */
trait HasFiles
{
    public function files(): MorphToMany
    {
        return $this->morphToMany(
            related: File::class,
            name: 'fileable',
            table: 'model_has_files',
            foreignPivotKey: 'fileable_id',
            relatedPivotKey: 'file_id',
            parentKey: 'id',
            relatedKey: 'id',
        );
    }
}
