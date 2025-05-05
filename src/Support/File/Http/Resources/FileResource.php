<?php

declare(strict_types=1);

namespace Support\File\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\File\Models\File;

/**
 * @property File $resource
 */
class FileResource extends JsonResource
{
    public function __construct(File $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type->value,
            'extension' => $this->resource->extension,
            'name' => $this->resource->name,
            'mime' => $this->resource->mime,
            'size' => $this->resource->size,
            'data' => $this->resource->data,
        ];
    }
}
