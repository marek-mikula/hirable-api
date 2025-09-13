<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Support\File\Enums\FileTypeEnum;
use Support\File\Http\Resources\FileResource;
use Support\File\Models\File;

/**
 * @property Candidate $resource
 */
class CandidateShowResource extends CandidateResource
{
    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations('files');

        $cvs = $this->resource->files->filter(fn (File $file): bool => $file->type === FileTypeEnum::CANDIDATE_CV);
        $otherFiles = $this->resource->files->filter(fn (File $file): bool => $file->type === FileTypeEnum::CANDIDATE_OTHER);

        return array_merge(parent::toArray($request), [
            'cvs' => new ResourceCollection(FileResource::class, $cvs),
            'otherFiles' => new ResourceCollection(FileResource::class, $otherFiles),
        ]);
    }
}
