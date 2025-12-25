<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Candidate\Http\Request\Data\CandidateStoreData;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Policies\CandidatePolicy;
use Support\File\Data\FileData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileConfigService;

class CandidateStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CandidatePolicy::store() */
        return $this->user()->can('store', Candidate::class);
    }

    public function rules(FileConfigService $fileConfigService): array
    {
        return [
            'cvs' => [
                'required',
                'array',
            ],
            'cvs.*' => [
                'required',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::CANDIDATE_CV))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::CANDIDATE_CV))
            ],
            'positionId' => [
                'nullable',
                'integer',
                Rule::editablePosition($this->user()),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'cvs' => __('model.candidate.cv'),
            'cvs.*' => __('model.candidate.cv'),
            'positionId' => __('common.assign_to_position'),
        ];
    }

    public function messages(): array
    {
        return [
            'cvs.*.required' => __('validation.many.required'),
            'cvs.*.file' => __('validation.many.file'),
            'cvs.*.max' => __('validation.many.max.file'),
            'cvs.*.extensions' => __('validation.many.extensions'),
        ];
    }

    public function toData(): CandidateStoreData
    {
        return new CandidateStoreData(
            cvs: array_map(FileData::make(...), $this->file('cvs', [])),
            positionId: $this->filled('positionId') ? (int) $this->input('positionId') : null,
        );
    }
}
