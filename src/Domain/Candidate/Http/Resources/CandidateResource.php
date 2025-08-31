<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Resources;

use App\Http\Resources\Collections\ResourceCollection;
use Domain\Candidate\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\File\Enums\FileTypeEnum;
use Support\File\Http\Resources\FileResource;
use Support\File\Models\File;

/**
 * @property Candidate $resource
 */
class CandidateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cvs = null;
        $otherFiles = null;

        if ($this->resource->relationLoaded('files')) {
            $cvs = $this->resource->files->filter(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_CV);
            $otherFiles = $this->resource->files->filter(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_OTHER);
        }

        return [
            'id' => $this->resource->id,
            'companyId' => $this->resource->company_id,
            'language' => $this->resource->language,
            'gender' => $this->resource->gender,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'fullName' => $this->resource->full_name,
            'email' => $this->resource->email,
            'phonePrefix' => $this->resource->phone_prefix,
            'phoneNumber' => $this->resource->phone_number,
            'phone' => $this->resource->phone,
            'linkedin' => $this->resource->linkedin,
            'linkedinUsername' => $this->resource->linkedin_username,
            'instagram' => $this->resource->instagram,
            'github' => $this->resource->github,
            'portfolio' => $this->resource->portfolio,
            'birthDate' => $this->resource->birth_date?->toIso8601String(),
            'experience' => $this->resource->experience,
            'tags' => $this->resource->tags,
            'createdAt' => $this->resource->created_at->toIso8601String(),
            'updatedAt' => $this->resource->updated_at->toIso8601String(),
            'cvs' => $this->when($cvs !== null, fn () => new ResourceCollection(FileResource::class, $cvs)),
            'otherFiles' => $this->when($otherFiles !== null, fn () => new ResourceCollection(FileResource::class, $otherFiles)),
        ];
    }
}
