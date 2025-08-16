<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Carbon\Carbon;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Http\Request\Data\LanguageRequirementData;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;
use Domain\Position\Services\PositionConfigService;
use Domain\Position\Validation\ValidateApprovalRequiredFields;
use Domain\Position\Validation\ValidateApprovalSelf;
use Domain\Position\Validation\ValidateApprovalOpen;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileConfigService;

class PositionStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::store() */
        return $this->user()->can('store', Position::class);
    }

    public function rules(
        FileConfigService $fileConfigService,
        PositionConfigService $positionConfigService,
    ): array {
        $user = $this->user();

        return [
            'operation' => [
                'required',
                'string',
                Rule::enum(PositionOperationEnum::class),
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'externName' => [
                'required',
                'string',
                'max:255'
            ],
            'department' => [
                'nullable',
                'string',
                'max:255',
            ],
            'field' => [
                'nullable',
                'string',
            ],
            'jobSeatsNum' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
            'description' => [
                'required',
                'string',
                'max:2000',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'salaryFrom' => [
                'required_without:salary',
                'nullable',
                'integer',
                'min:0',
            ],
            'salaryTo' => [
                'required_without:salary',
                'nullable',
                'integer',
                sprintf('min:%d', (int) $this->input('salaryFrom', 0))
            ],
            'salary' => [
                'required_without_all:salaryFrom,salaryTo',
                'nullable',
                'integer',
                'min:0',
            ],
            'salaryType' => [
                'required',
                'string',
            ],
            'salaryFrequency' => [
                'required',
                'string',
            ],
            'salaryCurrency' => [
                'required',
                'string',
            ],
            'salaryVar' => [
                'nullable',
                'string',
                'max:255',
            ],
            'minEducationLevel' => [
                'nullable',
                'string',
            ],
            'educationField' => [
                'nullable',
                'string',
                'max:255',
            ],
            'seniority' => [
                'array',
            ],
            'seniority.*' => [
                'required',
                'string',
            ],
            'experience' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],
            'hardSkills' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'organisationSkills' => [
                'required',
                'integer',
                'min:00',
                'max:100',
            ],
            'teamSkills' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'timeManagement' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'communicationSkills' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'leadership' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'note' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'workloads' => [
                'required',
                'array',
                'min:1',
            ],
            'workloads.*' => [
                'required',
                'string',
            ],
            'employmentRelationships' => [
                'required',
                'array',
                'min:1',
            ],
            'employmentRelationships.*' => [
                'required',
                'string',
            ],
            'employmentForms' => [
                'required',
                'array',
                'min:1',
            ],
            'employmentForms.*' => [
                'required',
                'string',
            ],
            'benefits' => [
                'array',
            ],
            'benefits.*' => [
                'required',
                'string',
            ],
            'files' => [
                'array',
                sprintf('max:%d', $fileConfigService->getFileMaxFiles(FileTypeEnum::POSITION_FILE)),
            ],
            'files.*' => [
                'required',
                Rule::file()
                    ->max($fileConfigService->getFileMaxSize(FileTypeEnum::POSITION_FILE))
                    ->extensions($fileConfigService->getFileExtensions(FileTypeEnum::POSITION_FILE))
            ],
            'languageRequirements' => [
                'array',
            ],
            'languageRequirements.*' => [
                'required',
                'array:language,level',
            ],
            'languageRequirements.*.language' => [
                'required',
                'string',
            ],
            'languageRequirements.*.level' => [
                'required',
                'string',
            ],
            'hiringManagers' => [
                'array',
            ],
            'hiringManagers.*' => [
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::HIRING_MANAGER)),
            ],
            'recruiters' => [
                'array',
            ],
            'recruiters.*' => [
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::RECRUITER)),
            ],
            'approvers' => [
                'array',
            ],
            'approvers.*' => [
                'required',
                'integer',
                Rule::user($user->company, $positionConfigService->getRolesForPositionRole(PositionRoleEnum::APPROVER)),
            ],
            'externalApprovers' => [
                'array',
            ],
            'externalApprovers.*' => [
                'required',
                'integer',
                Rule::exists(CompanyContact::class, 'id')->where('company_id', $user->company_id),
            ],
            'approveUntil' => [
                'required_with:approvers,externalApprovers',
                'nullable',
                Rule::date()->format('Y-m-d')->afterToday(),
            ],
            'approveMessage' => [
                'nullable',
                'string',
                'max:500',
            ],
            'hardSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'softSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'languageSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'experienceWeight' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'educationWeight' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'shareSalary' => [
                'boolean',
            ],
            'shareContact' => [
                'boolean',
            ],
        ];
    }

    public function after(): array
    {
        return [
            new ValidateApprovalRequiredFields(),
            new ValidateApprovalSelf($this->user()),
            new ValidateApprovalOpen(),
        ];
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
            'educationField' => __('model.position.educationField'),
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
            'experienceWeight' => __('model.position.experienceWeight'),
            'educationWeight' => __('model.position.educationWeight'),
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

    public function toData(): PositionData
    {
        return PositionData::from([
            'operation' => PositionOperationEnum::from((string) $this->input('operation')),
            'name' => (string) $this->input('name'),
            'externName' => (string) $this->input('externName'),
            'department' => $this->filled('department') ? (string) $this->input('department') : null,
            'field' => $this->filled('field') ? (string) $this->input('field') : null,
            'jobSeatsNum' => (int) $this->input('jobSeatsNum'),
            'description' => (string) $this->input('description'),
            'address' => $this->filled('address') ? (string) $this->input('address') : null,
            'salaryFrom' => $this->filled('salaryFrom') ? (int) $this->input('salaryFrom') : null,
            'salaryTo' => $this->filled('salaryTo') ? (int) $this->input('salaryTo') : null,
            'salary' => $this->filled('salary') ? (int) $this->input('salary') : null,
            'salaryType' => (string) $this->input('salaryType'),
            'salaryFrequency' => (string) $this->input('salaryFrequency'),
            'salaryCurrency' => (string) $this->input('salaryCurrency'),
            'salaryVar' => $this->filled('salaryVar') ? (string) $this->input('salaryVar') : null,
            'minEducationLevel' => $this->filled('minEducationLevel') ? (string) $this->input('minEducationLevel') : null,
            'educationField' => $this->filled('educationField') ? (string) $this->input('educationField') : null,
            'seniority' => $this->collect('seniority')->map(fn (mixed $val) => (string) $val)->all(),
            'experience' => $this->filled('experience') ? (int) $this->input('experience') : null,
            'hardSkills' => $this->filled('hardSkills') ? (string) $this->input('hardSkills') : null,
            'organisationSkills' => (int) $this->input('organisationSkills'),
            'teamSkills' => (int) $this->input('teamSkills'),
            'timeManagement' => (int) $this->input('timeManagement'),
            'communicationSkills' => (int) $this->input('communicationSkills'),
            'leadership' => (int) $this->input('leadership'),
            'note' => $this->filled('note') ? (string) $this->input('note') : null,
            'workloads' => $this->collect('workloads')->map(fn (mixed $val) => (string) $val)->all(),
            'employmentRelationships' => $this->collect('employmentRelationships')->map(fn (mixed $val) => (string) $val)->all(),
            'employmentForms' => $this->collect('employmentForms')->map(fn (mixed $val) => (string) $val)->all(),
            'benefits' => $this->collect('benefits')->map(fn (mixed $val) => (string) $val)->all(),
            'files' => $this->file('files', []),
            'languageRequirements' => $this->collect('languageRequirements')->map(function (array $item) {
                return LanguageRequirementData::from([
                    'language' => (string) $item['language'],
                    'level' => (string) $item['level'],
                ]);
            }),
            'hiringManagers' => $this->collect('hiringManagers')->map(fn (mixed $value) => (int) $value)->all(),
            'recruiters' => $this->collect('recruiters')->map(fn (mixed $value) => (int) $value)->all(),
            'approvers' => $this->collect('approvers')->map(fn (mixed $value) => (int) $value)->all(),
            'externalApprovers' => $this->collect('externalApprovers')->map(fn (mixed $value) => (int) $value)->all(),
            'approveUntil' => $this->filled('approveUntil') ? Carbon::createFromFormat('Y-m-d', (string) $this->input('approveUntil')) : null,
            'approveMessage' => $this->filled('approveMessage') ? (string) $this->input('approveMessage') : null,
            'hardSkillsWeight' => (int) $this->input('hardSkillsWeight'),
            'softSkillsWeight' => (int) $this->input('softSkillsWeight'),
            'languageSkillsWeight' => (int) $this->input('languageSkillsWeight'),
            'experienceWeight' => (int) $this->input('experienceWeight'),
            'educationWeight' => (int) $this->input('educationWeight'),
            'shareSalary' => (bool) $this->input('shareSalary'),
            'shareContact' => (bool) $this->input('shareContact'),
        ]);
    }
}
