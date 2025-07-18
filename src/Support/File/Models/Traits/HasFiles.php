<?php

declare(strict_types=1);

namespace Support\File\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Support\File\Models\File;

/**
 * @mixin Model
 *
 * @property-read Collection<File> $files
 * @property-read int|null $files_count
 */
trait HasFiles
{
    public function files(): MorphMany
    {
        return $this->morphMany(
            related: File::class,
            name: 'fileable',
            localKey: 'id',
        );
    }
}
