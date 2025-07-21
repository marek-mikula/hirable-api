<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\LanguageRequirementData;
use Domain\Position\Http\Request\Data\PositionUpdateData;
use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;
use Domain\Position\Services\PositionConfigService;
use Domain\Position\Validation\ValidateApprovalOpen;
use Domain\Position\Validation\ValidateApprovalRequiredFields;
use Domain\Position\Validation\ValidateApprovalSelf;
use Illuminate\Support\Arr;
use App\Rules\Rule;

class PositionUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::update() */
        return $this->user()->can('update', $this->route('position'));
    }

    public function rules(PositionConfigService $positionConfigService): array
    {
        $user = $this->user();

        /** @var Position $position */
        $position = $this->route('position');

        $keys = is_array($keys = $this->input('keys', [])) ? $keys : [];

        $operations = match ($position->state) {
            PositionStateEnum::APPROVAL_APPROVED => [PositionOperationEnum::OPEN],
            PositionStateEnum::OPENED => [PositionOperationEnum::SAVE],
            default => PositionOperationEnum::cases()
        };

        $fileCount = $position->loadCount('files')->files_count;

        return [
            'keys' => [
                'required',
                'array',
            ],
            'keys.*' => [
                'required',
                'string',
                Rule::in([
                    'name',
                    'externName',
                    'department',
                    'field',
                    'jobSeatsNum',
                    'description',
                    'address',
                    'salary',
                    'salaryType',
                    'salaryFrequency',
                    'salaryCurrency',
                    'salaryVar',
                    'minEducationLevel',
                    'seniority',
                    'experience',
                    'hardSkills',
                    'organisationSkills',
                    'teamSkills',
                    'timeManagement',
                    'communicationSkills',
                    'leadership',
                    'note',
                    'workloads',
                    'employmentRelationships',
                    'employmentForms',
                    'benefits',
                    'files',
                    'languageRequirements',
                    'hiringManagers',
                    'recruiters',
                    'approvers',
                    'externalApprovers',
                    'approveUntil',
                    'approveMessage',
                    'hardSkillsWeight',
                    'softSkillsWeight',
                    'languageSkillsWeight',
                    'shareSalary',
                    'shareContact',
                ])
            ],
            'operation' => [
                'required',
                'string',
                Rule::in(Arr::pluck($operations, 'value')),
            ],
            'name' => [
                Rule::excludeIf(!in_array('name', $keys)),
                'required',
                'string',
                'max:255'
            ],
            'externName' => [
                Rule::excludeIf(!in_array('externName', $keys)),
                'required',
                'string',
                'max:255'
            ],
            'department' => [
                Rule::excludeIf(!in_array('department', $keys)),
                'nullable',
                'string',
                'max:255',
            ],
            'field' => [
                Rule::excludeIf(!in_array('field', $keys)),
                'nullable',
                'string',
            ],
            'jobSeatsNum' => [
                Rule::excludeIf(!in_array('jobSeatsNum', $keys)),
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
            'description' => [
                Rule::excludeIf(!in_array('description', $keys)),
                'required',
                'string',
                'max:2000',
            ],
            'address' => [
                Rule::excludeIf(!in_array('address', $keys)),
                'nullable',
                'string',
                'max:255',
            ],
            'salaryFrom' => [
                Rule::excludeIf(!in_array('salary', $keys)),
                'required_without:salary',
                'nullable',
                'integer',
                'min:0',
            ],
            'salaryTo' => [
                Rule::excludeIf(!in_array('salary', $keys)),
                'required_without:salary',
                'nullable',
                'integer',
                sprintf('min:%d', (int) $this->input('salaryFrom', 0))
            ],
            'salary' => [
                Rule::excludeIf(!in_array('salary', $keys)),
                'required_without_all:salaryFrom,salaryTo',
                'nullable',
                'integer',
                'min:0',
            ],
            'salaryType' => [
                Rule::excludeIf(!in_array('salaryType', $keys)),
                'required',
                'string',
            ],
            'salaryFrequency' => [
                Rule::excludeIf(!in_array('salaryFrequency', $keys)),
                'required',
                'string',
            ],
            'salaryCurrency' => [
                Rule::excludeIf(!in_array('salaryCurrency', $keys)),
                'required',
                'string',
            ],
            'salaryVar' => [
                Rule::excludeIf(!in_array('salaryVar', $keys)),
                'nullable',
                'string',
                'max:255',
            ],
            'minEducationLevel' => [
                Rule::excludeIf(!in_array('minEducationLevel', $keys)),
                'nullable',
                'string',
            ],
            'seniority' => [
                Rule::excludeIf(!in_array('seniority', $keys)),
                'array',
            ],
            'seniority.*' => [
                Rule::excludeIf(!in_array('seniority', $keys)),
                'required',
                'string',
            ],
            'experience' => [
                Rule::excludeIf(!in_array('experience', $keys)),
                'nullable',
                'integer',
                'min:0',
            ],
            'hardSkills' => [
                Rule::excludeIf(!in_array('hardSkills', $keys)),
                'nullable',
                'string',
                'max:2000',
            ],
            'organisationSkills' => [
                Rule::excludeIf(!in_array('organisationSkills', $keys)),
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'teamSkills' => [
                Rule::excludeIf(!in_array('teamSkills', $keys)),
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'timeManagement' => [
                Rule::excludeIf(!in_array('timeManagement', $keys)),
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'communicationSkills' => [
                Rule::excludeIf(!in_array('communicationSkills', $keys)),
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'leadership' => [
                Rule::excludeIf(!in_array('leadership', $keys)),
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'note' => [
                Rule::excludeIf(!in_array('note', $keys)),
                'nullable',
                'string',
                'max:2000',
            ],
            'workloads' => [
                Rule::excludeIf(!in_array('workloads', $keys)),
                'required',
                'array',
                'min:1',
            ],
            'workloads.*' => [
                Rule::excludeIf(!in_array('workloads', $keys)),
                'required',
                'string',
            ],
            'employmentRelationships' => [
                Rule::excludeIf(!in_array('employmentRelationships', $keys)),
                'required',
                'array',
                'min:1',
            ],
            'employmentRelationships.*' => [
                Rule::excludeIf(!in_array('employmentRelationships', $keys)),
                'required',
                'string',
            ],
            'employmentForms' => [
                Rule::excludeIf(!in_array('employmentForms', $keys)),
                'required',
                'array',
                'min:1',
            ],
            'employmentForms.*' => [
                Rule::excludeIf(!in_array('employmentForms', $keys)),
                'required',
                'string',
            ],
            'benefits' => [
                Rule::excludeIf(!in_array('benefits', $keys)),
                'array',
            ],
            'benefits.*' => [
                Rule::excludeIf(!in_array('benefits', $keys)),
                'required',
                'string',
            ],
            'files' => [
                Rule::excludeIf(!in_array('files', $keys)),
                'array',
                sprintf('max:%d', $positionConfigService->getMaxFiles() - $fileCount),
            ],
            'files.*' => [
                'required',
                Rule::file()
                    ->max($positionConfigService->getMaxFileSize())
                    ->extensions($positionConfigService->getAllowedFileExtensions())
            ],
            'languageRequirements' => [
                Rule::excludeIf(!in_array('languageRequirements', $keys)),
                'array',
            ],
            'languageRequirements.*' => [
                Rule::excludeIf(!in_array('languageRequirements', $keys)),
                'required',
                'array:language,level',
            ],
            'languageRequirements.*.language' => [
                Rule::excludeIf(!in_array('languageRequirements', $keys)),
                'required',
                'string',
            ],
            'languageRequirements.*.level' => [
                Rule::excludeIf(!in_array('languageRequirements', $keys)),
                'required',
                'string',
            ],
            'hiringManagers' => [
                Rule::excludeIf(!in_array('hiringManagers', $keys)),
                'array',
            ],
            'hiringManagers.*' => [
                Rule::excludeIf(!in_array('hiringManagers', $keys)),
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::HIRING_MANAGER)),
            ],
            'recruiters' => [
                Rule::excludeIf(!in_array('recruiters', $keys)),
                'array',
            ],
            'recruiters.*' => [
                Rule::excludeIf(!in_array('recruiters', $keys)),
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::RECRUITER)),
            ],
            'approvers' => [
                Rule::excludeIf(!in_array('approvers', $keys)),
                'array',
            ],
            'approvers.*' => [
                Rule::excludeIf(!in_array('approvers', $keys)),
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::APPROVER)),
            ],
            'externalApprovers' => [
                Rule::excludeIf(!in_array('externalApprovers', $keys)),
                'array',
            ],
            'externalApprovers.*' => [
                Rule::excludeIf(!in_array('externalApprovers', $keys)),
                'required',
                'integer',
                Rule::exists(CompanyContact::class, 'id')->where('company_id', $user->company_id),
            ],
            'approveUntil' => [
                Rule::excludeIf(!in_array('approveUntil', $keys)),
                'required_with:approvers,externalApprovers',
                'nullable',
                Rule::date()->format('Y-m-d')->afterToday(),
            ],
            'approveMessage' => [
                Rule::excludeIf(!in_array('approveMessage', $keys)),
                'nullable',
                'string',
                'max:500',
            ],
            'hardSkillsWeight' => [
                Rule::excludeIf(!in_array('hardSkillsWeight', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'softSkillsWeight' => [
                Rule::excludeIf(!in_array('softSkillsWeight', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'languageSkillsWeight' => [
                Rule::excludeIf(!in_array('languageSkillsWeight', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'shareSalary' => [
                Rule::excludeIf(!in_array('shareSalary', $keys)),
                'boolean',
            ],
            'shareContact' => [
                Rule::excludeIf(!in_array('shareContact', $keys)),
                'boolean',
            ],
        ];
    }

    public function after(): array
    {
        /** @var Position $position */
        $position = $this->route('position');

        if ($position->state === PositionStateEnum::OPENED || $position->state === PositionStateEnum::APPROVAL_APPROVED) {
            return [];
        }

        return array_filter([
            new ValidateApprovalRequiredFields(),
            new ValidateApprovalSelf($this->user()),
            new ValidateApprovalOpen(),
        ]);
    }

    public function attributes(): array
    {
        return [
            'operation' => __('model.common.operation'),
            'name' => __('model.position.name'),
            'externName' => __('model.position.externName'),
            'department' => __('model.position.department'),
            'field' => __('model.position.field'),
            'jobSeatsNum' => __('model.position.jobSeatsNum'),
            'description' => __('model.position.description'),
            'address' => __('model.position.address'),
            'salaryFrom' => __('model.position.salaryFrom'),
            'salaryTo' => __('model.position.salaryTo'),
            'salary' => __('model.position.salary'),
            'salaryType' => __('model.position.salaryType'),
            'salaryFrequency' => __('model.position.salaryFrequency'),
            'salaryCurrency' => __('model.position.salaryCurrency'),
            'salaryVar' => __('model.position.salaryVar'),
            'minEducationLevel' => __('model.position.minEducationLevel'),
            'seniority' => __('model.position.seniority'),
            'seniority.*' => __('model.position.seniority'),
            'experience' => __('model.position.experience'),
            'hardSkills' => __('model.position.hardSkills'),
            'organisationSkills' => __('model.position.organisationSkills'),
            'teamSkills' => __('model.position.teamSkills'),
            'timeManagement' => __('model.position.timeManagement'),
            'communicationSkills' => __('model.position.communicationSkills'),
            'leadership' => __('model.position.leadership'),
            'note' => __('model.common.note'),
            'workloads' => __('model.position.workload'),
            'workloads.*' => __('model.position.workload'),
            'employmentRelationships' => __('model.position.employmentRelationship'),
            'employmentRelationships-*' => __('model.position.employmentRelationship'),
            'employmentForms' => __('model.position.employmentForm'),
            'employmentForms.*' => __('model.position.employmentForm'),
            'benefits' => __('model.position.benefits'),
            'benefits.*' => __('model.position.benefits'),
            'files' => __('model.common.files'),
            'files.*' => __('model.common.files'),
            'languageRequirements' => __('model.position.languageRequirements'),
            'languageRequirements.*' => __('model.position.languageRequirements'),
            'languageRequirements.*.language' => __('model.position.languageRequirements'),
            'languageRequirements.*.level' => __('model.position.languageRequirements'),
            'hiringManagers' => __('model.position.hiringManagers'),
            'hiringManagers.*' => __('model.position.hiringManagers'),
            'recruiters' => __('model.position.recruiters'),
            'recruiters.*' => __('model.position.recruiters'),
            'approvers' => __('model.position.approvers'),
            'approvers.*' => __('model.position.approvers'),
            'externalApprovers' => __('model.position.externalApprovers'),
            'externalApprovers.*' => __('model.position.externalApprovers'),
            'approveUntil' => __('model.position.approveUntil'),
            'approveMessage' => __('model.position.approveMessage'),
            'hardSkillsWeight' => __('model.position.hardSkillsWeight'),
            'softSkillsWeight' => __('model.position.softSkillsWeight'),
            'languageSkillsWeight' => __('model.position.languageSkillsWeight'),
            'shareSalary' => __('model.position.shareSalary'),
            'shareContact' => __('model.position.shareContact'),
        ];
    }

    public function messages(): array
    {
        return [
            'files.*.required' => __('validation.many.required'),
            'files.*.file' => __('validation.many.file'),
            'files.*.max' => __('validation.many.max.file'),
            'files.*.extensions' => __('validation.many.extensions'),
        ];
    }

    public function toData(): PositionUpdateData
    {
        $keys = $this->array('keys');

        return PositionUpdateData::from([
            'operation' => PositionOperationEnum::from((string) $this->input('operation')),
            'keys' => $keys,
            'name' => in_array('name', $keys) ? (string) $this->input('name') : null,
            'externName' => in_array('externName', $keys) ? (string) $this->input('externName') : null,
            'department' => in_array('department', $keys) ? ($this->filled('department') ? (string) $this->input('department') : null) : null,
            'field' => in_array('field', $keys) ? ($this->filled('field') ? (string) $this->input('field') : null) : null,
            'jobSeatsNum' => in_array('jobSeatsNum', $keys) ? ((int) $this->input('jobSeatsNum')) : null,
            'description' => in_array('description', $keys) ? ((string) $this->input('description')) : null,
            'address' => in_array('address', $keys) ? ($this->filled('address') ? (string) $this->input('address') : null) : null,
            'salaryFrom' => in_array('salary', $keys) ? ($this->filled('salaryFrom') ? (int) $this->input('salaryFrom') : null) : null,
            'salaryTo' => in_array('salary', $keys) ? ($this->filled('salaryTo') ? (int) $this->input('salaryTo') : null) : null,
            'salary' => in_array('salary', $keys) ? ($this->filled('salary') ? (int) $this->input('salary') : null) : null,
            'salaryType' => in_array('salaryType', $keys) ? ((string) $this->input('salaryType')) : null,
            'salaryFrequency' => in_array('salaryFrequency', $keys) ? ((string) $this->input('salaryFrequency')) : null,
            'salaryCurrency' => in_array('salaryCurrency', $keys) ? ((string) $this->input('salaryCurrency')) : null,
            'salaryVar' => in_array('salaryVar', $keys) ? ($this->filled('salaryVar') ? (string) $this->input('salaryVar') : null) : null,
            'minEducationLevel' => in_array('minEducationLevel', $keys) ? ($this->filled('minEducationLevel') ? (string) $this->input('minEducationLevel') : null) : null,
            'seniority' => in_array('seniority', $keys) ? ($this->collect('seniority')->map(fn (mixed $val) => (string) $val)->all()) : [],
            'experience' => in_array('experience', $keys) ? ($this->filled('experience') ? (int) $this->input('experience') : null) : null,
            'hardSkills' => in_array('hardSkills', $keys) ? ($this->filled('hardSkills') ? (string) $this->input('hardSkills') : null) : null,
            'organisationSkills' => in_array('organisationSkills', $keys) ? ((int) $this->input('organisationSkills')) : null,
            'teamSkills' => in_array('teamSkills', $keys) ? ((int) $this->input('teamSkills')) : null,
            'timeManagement' => in_array('timeManagement', $keys) ? ((int) $this->input('timeManagement')) : null,
            'communicationSkills' => in_array('communicationSkills', $keys) ? ((int) $this->input('communicationSkills')) : null,
            'leadership' => in_array('leadership', $keys) ? ((int) $this->input('leadership')) : null,
            'note' => in_array('note', $keys) ? ($this->filled('note') ? (string) $this->input('note') : null) : null,
            'workloads' => in_array('workloads', $keys) ? ($this->collect('workloads')->map(fn (mixed $val) => (string) $val)->all()) : [],
            'employmentRelationships' => in_array('employmentRelationships', $keys) ? ($this->collect('employmentRelationships')->map(fn (mixed $val) => (string) $val)->all()) : [],
            'employmentForms' => in_array('employmentForms', $keys) ? ($this->collect('employmentForms')->map(fn (mixed $val) => (string) $val)->all()) : [],
            'benefits' => in_array('benefits', $keys) ? ($this->collect('benefits')->map(fn (mixed $val) => (string) $val)->all()) : [],
            'files' => in_array('files', $keys) ? ($this->file('files', [])) : [],
            'languageRequirements' => in_array('languageRequirements', $keys) ? $this->collect('languageRequirements')->map(function (array $item) {
                return LanguageRequirementData::from([
                    'language' => (string) $item['language'],
                    'level' => (string) $item['level'],
                ]);
            }) : [],
            'hiringManagers' => in_array('hiringManagers', $keys) ? ($this->collect('hiringManagers')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'recruiters' => in_array('recruiters', $keys) ? ($this->collect('recruiters')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'approvers' => in_array('approvers', $keys) ? ($this->collect('approvers')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'externalApprovers' => in_array('externalApprovers', $keys) ? ($this->collect('externalApprovers')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'approveUntil' => in_array('approveUntil', $keys) ? ($this->filled('approveUntil') ? Carbon::createFromFormat('Y-m-d', (string) $this->input('approveUntil')) : null) : null,
            'approveMessage' => in_array('approveMessage', $keys) ? ($this->filled('approveMessage') ? (string) $this->input('approveMessage') : null) : null,
            'hardSkillsWeight' => in_array('hardSkillsWeight', $keys) ? ((int) $this->input('hardSkillsWeight')) : null,
            'softSkillsWeight' => in_array('softSkillsWeight', $keys) ? ((int) $this->input('softSkillsWeight')) : null,
            'languageSkillsWeight' => in_array('languageSkillsWeight', $keys) ? ((int) $this->input('languageSkillsWeight')) : null,
            'shareSalary' => in_array('shareSalary', $keys) ? (bool) $this->input('shareSalary') : null,
            'shareContact' => in_array('shareContact', $keys) ? (bool) $this->input('shareContact') : null,
        ]);
    }
}
