<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request;

use App\Enums\LanguageEnum;
use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Http\Request\Data\CandidateUpdateData;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Policies\CandidatePolicy;
use Domain\Candidate\Services\CandidateConfigService;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileConfigService;

class CandidateUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CandidatePolicy::update() */
        return $this->user()->can('update', $this->route('candidate'));
    }

    public function rules(
        CandidateConfigService $candidateConfigService,
        FileConfigService $fileConfigService,
    ): array {
        /** @var Candidate $candidate */
        $candidate = $this->route('candidate');

        $keys = is_array($keys = $this->input('keys', [])) ? $keys : [];

        $otherFilesCount = (int) $candidate->loadCount('otherFiles')->other_files_count;

        return [
            'keys' => [
                'required',
                'array',
            ],
            'keys.*' => [
                'required',
                'string',
                Rule::in([
                    'firstname',
                    'lastname',
                    'gender',
                    'language',
                    'email',
                    'phone',
                    'linkedin',
                    'instagram',
                    'github',
                    'portfolio',
                    'birthDate',
                    'tags',
                    'cv',
                    'otherFiles'
                ])
            ],
            'firstname' => [
                Rule::excludeIf(!in_array('firstname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                Rule::excludeIf(!in_array('lastname', $keys)),
                'required',
                'string',
                'max:255',
            ],
            'gender' => [
                Rule::excludeIf(!in_array('gender', $keys)),
                'nullable',
                'string',
            ],
            'language' => [
                Rule::excludeIf(!in_array('language', $keys)),
                'required',
                'string',
                Rule::enum(LanguageEnum::class),
            ],
            'email' => [
                Rule::excludeIf(!in_array('email', $keys)),
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Candidate::class, 'email')
                    ->where('company_id', $this->user()->company_id)
                    ->ignore($candidate->id),
            ],
            'phonePrefix' => [
                Rule::excludeIf(!in_array('phone', $keys)),
                'required',
                'string',
            ],
            'phoneNumber' => [
                Rule::excludeIf(!in_array('phone', $keys)),
                'required',
                'string',
                Rule::phone()
            ],
            'linkedin' => [
                Rule::excludeIf(!in_array('linkedin', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
                Rule::linkedin(),
            ],
            'instagram' => [
                Rule::excludeIf(!in_array('instagram', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
            ],
            'github' => [
                Rule::excludeIf(!in_array('github', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
            ],
            'portfolio' => [
                Rule::excludeIf(!in_array('portfolio', $keys)),
                'nullable',
                'string',
                'url',
                'max:255',
            ],
            'birthDate' => [
                Rule::excludeIf(!in_array('birthDate', $keys)),
                'nullable',
                'string',
                'date_format:Y-m-d',
            ],
            'tags' => [
                Rule::excludeIf(!in_array('tags', $keys)),
                'array',
                sprintf('max:%d', $candidateConfigService->getMaxTags()),
            ],
            'tags.*' => [
                Rule::excludeIf(!in_array('tags', $keys)),
                'required',
                'string',
                'min:2',
                'max:30',
            ],
            'cv' => [
                Rule::excludeIf(!in_array('cv', $keys)),
                'nullable',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::CANDIDATE_CV))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::CANDIDATE_CV))
            ],
            'otherFiles' => [
                Rule::excludeIf(!in_array('otherFiles', $keys)),
                'array',
                sprintf('max:%d', $fileConfigService->getFileMaxFiles(FileTypeEnum::CANDIDATE_OTHER) - $otherFilesCount),
            ],
            'otherFiles.*' => [
                Rule::excludeIf(!in_array('otherFiles', $keys)),
                'required',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::CANDIDATE_OTHER))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::CANDIDATE_OTHER))
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'firstname' => __('model.common.firstname'),
            'lastname' => __('model.common.lastname'),
            'gender' => __('model.common.gender'),
            'language' => __('model.common.language'),
            'email' => __('model.common.email'),
            'phonePrefix' => __('model.common.phonePrefix'),
            'phoneNumber' => __('model.common.phoneNumber'),
            'linkedin' => __('model.common.linkedin'),
            'instagram' => __('model.common.instagram'),
            'github' => __('model.common.github'),
            'portfolio' => __('model.common.portfolio'),
            'birthDate' => __('model.common.birthDate'),
            'tags' => __('model.common.tags'),
            'tags.*' => __('model.common.tags'),
            'cv' => __('model.candidate.cv'),
            'otherFiles' => __('model.candidate.otherFiles'),
            'otherFiles.*' => __('model.candidate.otherFiles'),
        ];
    }

    public function messages(): array
    {
        return [
            'otherFiles.*.required' => __('validation.many.required'),
            'otherFiles.*.file' => __('validation.many.file'),
            'otherFiles.*.max' => __('validation.many.max.file'),
            'otherFiles.*.extensions' => __('validation.many.extensions'),
        ];
    }

    public function toData(): CandidateUpdateData
    {
        $keys = $this->array('keys');

        return new CandidateUpdateData(
            keys: $keys,
            firstname: in_array('firstname', $keys)
                ? (string) $this->input('firstname')
                : null,
            lastname: in_array('lastname', $keys)
                ? (string) $this->input('lastname')
                : null,
            gender: in_array('gender', $keys) && $this->filled('gender')
                ? $this->enum('gender', GenderEnum::class)
                : null,
            language: in_array('language', $keys)
                ? $this->enum('language', LanguageEnum::class)
                : null,
            email: in_array('email', $keys)
                ? (string) $this->input('email')
                : null,
            phonePrefix: in_array('phone', $keys)
                ? (string) $this->input('phonePrefix')
                : null,
            phoneNumber: in_array('phone', $keys)
                ? (string) $this->input('phoneNumber')
                : null,
            linkedin: in_array('linkedin', $keys) && $this->filled('linkedin')
                ? (string) $this->input('linkedin')
                : null,
            instagram: in_array('instagram', $keys) && $this->filled('instagram')
                ? (string) $this->input('instagram')
                : null,
            github: in_array('github', $keys) && $this->filled('github')
                ? (string) $this->input('github')
                : null,
            portfolio: in_array('portfolio', $keys) && $this->filled('portfolio')
                ? (string) $this->input('portfolio')
                : null,
            birthDate: in_array('birthDate', $keys) && $this->filled('birthDate')
                ? $this->date('birthDate', 'Y-m-d')
                : null,
            tags: in_array('tags', $keys) && $this->filled('tags')
                ? $this->collect('tags')->map(fn (mixed $tag) => (string) $tag)->all()
                : [],
            cv: in_array('cv', $keys) && $this->hasFile('cv')
                ? $this->file('cv')
                : null,
            otherFiles: in_array('otherFiles', $keys) && $this->hasFile('otherFiles')
                ? $this->file('otherFiles', [])
                : [],
        );
    }
}
