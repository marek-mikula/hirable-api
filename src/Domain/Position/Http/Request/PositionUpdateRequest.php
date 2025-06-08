<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Domain\Company\Models\CompanyContact;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\LanguageRequirementData;
use Domain\Position\Http\Request\Data\PositionUpdateData;
use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;
use Domain\Position\Validation\ValidateApprovalDuplicates;
use Domain\Position\Validation\ValidateApprovalOpen;
use Domain\Position\Validation\ValidateApprovalRequiredFields;
use Domain\Position\Validation\ValidateApprovalSelf;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PositionUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::update() */
        return $this->user()->can('update', $this->route('position'));
    }

    public function rules(): array
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
                    'department',
                    'field',
                    'jobSeatsNum',
                    'description',
                    'isTechnical',
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
                    'approvers',
                    'externalApprovers',
                    'approveUntil',
                    'hardSkillsWeight',
                    'softSkillsWeight',
                    'languageSkillsWeight',
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
            'isTechnical' => [
                Rule::excludeIf(!in_array('isTechnical', $keys)),
                'boolean',
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
                'nullable',
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
                'max:10',
            ],
            'teamSkills' => [
                Rule::excludeIf(!in_array('teamSkills', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'timeManagement' => [
                Rule::excludeIf(!in_array('timeManagement', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'communicationSkills' => [
                Rule::excludeIf(!in_array('communicationSkills', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
            ],
            'leadership' => [
                Rule::excludeIf(!in_array('leadership', $keys)),
                'required',
                'integer',
                'min:0',
                'max:10',
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
            ],
            'files.*' => [
                'required',
                Rule::file()
                    ->max('10MB')
                    ->extensions(['pdf', 'docx', 'xlsx'])
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
                Rule::exists(User::class, 'id')->where('company_id', $user->company_id),
            ],
            'approvers' => [
                Rule::excludeIf(!in_array('approvers', $keys)),
                'array',
            ],
            'approvers.*' => [
                Rule::excludeIf(!in_array('approvers', $keys)),
                'required',
                'integer',
                Rule::exists(User::class, 'id')->where('company_id', $user->company_id),
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
                'required_with:hiringManagers,approvers,externalApprovers',
                'nullable',
                Rule::date()->format('Y-m-d')->afterToday(),
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
            new ValidateApprovalDuplicates(),
            new ValidateApprovalSelf($this->user()),
            new ValidateApprovalOpen(),
        ]);
    }

    public function toData(): PositionUpdateData
    {
        $keys = $this->array('keys');

        return PositionUpdateData::from([
            'operation' => PositionOperationEnum::from((string) $this->input('operation')),
            'keys' => $keys,
            'name' => in_array('name', $keys) ? (string) $this->input('name') : null,
            'department' => in_array('department', $keys) ? ($this->filled('department') ? (string) $this->input('department') : null) : null,
            'field' => in_array('field', $keys) ? ($this->filled('field') ? (string) $this->input('field') : null) : null,
            'jobSeatsNum' => in_array('jobSeatsNum', $keys) ? ((int) $this->input('jobSeatsNum')) : null,
            'description' => in_array('description', $keys) ? ((string) $this->input('description')) : null,
            'isTechnical' => in_array('description', $keys) ? $this->boolean('isTechnical') : null,
            'address' => in_array('address', $keys) ? ($this->filled('address') ? (string) $this->input('address') : null) : null,
            'salaryFrom' => in_array('salary', $keys) ? ($this->filled('salaryFrom') ? (int) $this->input('salaryFrom') : null) : null,
            'salaryTo' => in_array('salary', $keys) ? ($this->filled('salaryTo') ? (int) $this->input('salaryTo') : null) : null,
            'salary' => in_array('salary', $keys) ? ($this->filled('salary') ? (int) $this->input('salary') : null) : null,
            'salaryType' => in_array('salaryType', $keys) ? ((string) $this->input('salaryType')) : null,
            'salaryFrequency' => in_array('salaryFrequency', $keys) ? ((string) $this->input('salaryFrequency')) : null,
            'salaryCurrency' => in_array('salaryCurrency', $keys) ? ((string) $this->input('salaryCurrency')) : null,
            'salaryVar' => in_array('salaryVar', $keys) ? ($this->filled('salaryVar') ? (string) $this->input('salaryVar') : null) : null,
            'minEducationLevel' => in_array('minEducationLevel', $keys) ? ($this->filled('minEducationLevel') ? (string) $this->input('minEducationLevel') : null) : null,
            'seniority' => in_array('seniority', $keys) ? ($this->filled('seniority') ? (string) $this->input('seniority') : null) : null,
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
            'approvers' => in_array('approvers', $keys) ? ($this->collect('approvers')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'externalApprovers' => in_array('externalApprovers', $keys) ? ($this->collect('externalApprovers')->map(fn (mixed $value) => (int) $value)->all()) : [],
            'approveUntil' => in_array('approveUntil', $keys) ? ($this->filled('approveUntil') ? Carbon::createFromFormat('Y-m-d', (string) $this->input('approveUntil')) : null) : null,
            'hardSkillsWeight' => in_array('hardSkillsWeight', $keys) ? ((int) $this->input('hardSkillsWeight')) : null,
            'softSkillsWeight' => in_array('softSkillsWeight', $keys) ? ((int) $this->input('softSkillsWeight')) : null,
            'languageSkillsWeight' => in_array('languageSkillsWeight', $keys) ? ((int) $this->input('languageSkillsWeight')) : null,
        ]);
    }
}
