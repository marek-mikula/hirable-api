<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Http\Request\Data\LanguageRequirementData;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Validation\ValidateApprovalDuplicates;
use Domain\Position\Validation\ValidateApprovalRequiredFields;
use Domain\Position\Validation\ValidateApprovalSelf;
use Domain\Position\Validation\ValidateApprovalOpen;
use Domain\User\Models\User;
use Illuminate\Validation\Rule;

class PositionStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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
            'isTechnical' => [
                'boolean',
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
            'seniority' => [
                'nullable',
                'string',
            ],
            'experience' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'hardSkills' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'organisationSkills' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'teamSkills' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'timeManagement' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'communicationSkills' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'leadership' => [
                'required',
                'integer',
                'min:0',
                'max:10',
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
            ],
            'files.*' => [
                'required',
                Rule::file()
                    ->max('10MB')
                    ->extensions(['pdf', 'docx', 'xlsx'])
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
                Rule::exists(User::class, 'id')->where('company_id', $user->company_id),
            ],
            'approvers' => [
                'array',
            ],
            'approvers.*' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')->where('company_id', $user->company_id),
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
                'required_with:hiringManagers,approvers,externalApprovers',
                'nullable',
                Rule::date()->format('Y-m-d')->afterToday(),
            ],
            'hardSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'softSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'languageSkillsWeight' => [
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
        ];
    }

    public function after(): array
    {
        return [
            new ValidateApprovalRequiredFields(),
            new ValidateApprovalDuplicates(),
            new ValidateApprovalSelf($this->user()),
            new ValidateApprovalOpen(),
        ];
    }

    public function toData(): PositionData
    {
        return PositionData::from([
            'operation' => PositionOperationEnum::from((string) $this->input('operation')),
            'name' => (string) $this->input('name'),
            'department' => $this->filled('department') ? (string) $this->input('department') : null,
            'field' => $this->filled('field') ? (string) $this->input('field') : null,
            'jobSeatsNum' => (int) $this->input('jobSeatsNum'),
            'description' => (string) $this->input('description'),
            'isTechnical' => $this->boolean('isTechnical'),
            'address' => $this->filled('address') ? (string) $this->input('address') : null,
            'salaryFrom' => $this->filled('salaryFrom') ? (int) $this->input('salaryFrom') : null,
            'salaryTo' => $this->filled('salaryTo') ? (int) $this->input('salaryTo') : null,
            'salary' => $this->filled('salary') ? (int) $this->input('salary') : null,
            'salaryType' => (string) $this->input('salaryType'),
            'salaryFrequency' => (string) $this->input('salaryFrequency'),
            'salaryCurrency' => (string) $this->input('salaryCurrency'),
            'salaryVar' => $this->filled('salaryVar') ? (string) $this->input('salaryVar') : null,
            'minEducationLevel' => $this->filled('minEducationLevel') ? (string) $this->input('minEducationLevel') : null,
            'seniority' => $this->filled('seniority') ? (string) $this->input('seniority') : null,
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
            'approvers' => $this->collect('approvers')->map(fn (mixed $value) => (int) $value)->all(),
            'externalApprovers' => $this->collect('externalApprovers')->map(fn (mixed $value) => (int) $value)->all(),
            'approveUntil' => $this->filled('approveUntil') ? Carbon::createFromFormat('Y-m-d', (string) $this->input('approveUntil')) : null,
            'hardSkillsWeight' => (int) $this->input('hardSkillsWeight'),
            'softSkillsWeight' => (int) $this->input('softSkillsWeight'),
            'languageSkillsWeight' => (int) $this->input('languageSkillsWeight'),
        ]);
    }
}
