<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 *
 * @property-read Collection<File> $files
 */
trait HasFiles
{
    public function files(): MorphMany
    {
        return $this->morphMany(
            related: File::class,
            name: 'fileable',
            type: 'fileable_type',
            id: 'fileable_id',
            localKey: 'id',
        );
    }
}
