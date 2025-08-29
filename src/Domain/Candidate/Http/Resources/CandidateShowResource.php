<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources;

use App\Http\Resources\Traits\ChecksRelations;
use Domain\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Support\File\Enums\FileTypeEnum;
use Support\File\Http\Resources\Collections\FileCollection;
use Support\File\Models\File;

/**
 * @property Candidate $resource
 */
class CandidateShowResource extends CandidateResource
{
    use ChecksRelations;

    public function toArray(Request $request): array
    {
        $this->checkLoadedRelations(['files']);

        $cvs = $this->resource->files->filter(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_CV);
        $otherFiles = $this->resource->files->filter(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_OTHER);

        return array_merge(parent::toArray($request), [
            'cvs' => new FileCollection($cvs),
            'otherFiles' => new FileCollection($otherFiles),
        ]);
    }
}
