<?php

declare(strict_types=1);

namespace Support\File\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Support\File\Models\File;

/**
 * @property File $resource
 */
class FileResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'extension' => $this->resource->extension,
            'name' => $this->resource->name,
            'mime' => $this->resource->mime,
            'size' => $this->resource->size,
        ];
    }
}
