<?php

declare(strict_types=1);

namespace Domain\Application\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\Rule;
use Domain\Application\Data\ApplyData;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileConfigService;

class ApplicationApplyRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(FileConfigService $fileConfigService): array
    {
        return [
            'firstname' => [
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'phonePrefix' => [
                'required',
                'string',
            ],
            'phoneNumber' => [
                'required',
                'string',
                Rule::phone()
            ],
            'linkedin' => [
                'nullable',
                'string',
                'url',
                'max:255',
                Rule::linkedin(),
            ],
            'cv' => [
                'required',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::CANDIDATE_CV))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::CANDIDATE_CV))
            ],
            'otherFiles' => [
                'array',
                sprintf('max:%d', $fileConfigService->getFileMaxFiles(FileTypeEnum::CANDIDATE_OTHER))
            ],
            'otherFiles.*' => [
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
            'email' => __('model.common.email'),
            'phonePrefix' => __('model.common.phonePrefix'),
            'phoneNumber' => __('model.common.phoneNumber'),
            'linkedin' => __('model.candidate.linkedin'),
            'cv' => __('model.candidate.cv'),
            'otherFiles' => __('model.common.files'),
            'otherFiles.*' => __('model.common.files'),
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

    public function toData(): ApplyData
    {
        return new ApplyData(
            firstname: (string) $this->input('firstname'),
            lastname: (string) $this->input('lastname'),
            email: (string) $this->input('email'),
            phonePrefix: (string) $this->input('phonePrefix'),
            phoneNumber: (string) $this->input('phoneNumber'),
            linkedin: $this->filled('linkedin') ? (string) $this->input('linkedin') : null,
            cv: $this->file('cv'),
            otherFiles: $this->file('otherFiles', []),
        );
    }
}
