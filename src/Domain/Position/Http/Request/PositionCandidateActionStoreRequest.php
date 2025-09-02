<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Http\Request\Data\ActionData;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Policies\PositionCandidateActionPolicy;

class PositionCandidateActionStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateActionPolicy::store() */
        return $this->user()->can('store', [
            PositionCandidateAction::class,
            $this->route('positionCandidate'),
            $this->route('position')
        ]);
    }

    public function rules(): array
    {
        $type = $this->enum('type', ActionTypeEnum::class);

        $actionFields = match ($type) {
            ActionTypeEnum::INTERVIEW => [
                'date' => [
                    'required',
                    'string',
                    'date_format:Y-m-d',
                ],
                'timeStart' => [
                    'required',
                    'string',
                    'date_format:H:i',
                ],
                'timeEnd' => [
                    'required',
                    'string',
                    'date_format:H:i',
                ],
                'place' => [
                    Rule::excludeIf($this->input('interviewForm') !== 'personal'),
                    'required',
                    'string',
                    'max:255',
                ],
                'interviewForm' => [
                    'required',
                    'string',
                ],
                'interviewType' => [
                    'required',
                    'string',
                ],
                'unavailable' => [
                    Rule::excludeIf($this->input('interviewType') !== 'screening'),
                    'boolean',
                ],
                'noShow' => [
                    Rule::excludeIf($this->input('interviewType') === 'screening'),
                    'boolean',
                ],
            ],
            ActionTypeEnum::TEST => [
                'testType' => [
                    'required',
                    'string',
                ],
                'instructions' => [
                    'required',
                    'string',
                    'max:500',
                ],
                'evaluation' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ],
            ActionTypeEnum::TASK => [
                'date' => [
                    'nullable',
                    'required_with:timeEnd',
                    'string',
                    'date_format:Y-m-d',
                ],
                'timeEnd' => [
                    'nullable',
                    'required_with:date',
                    'string',
                    'date_format:H:i',
                ],
                'instructions' => [
                    'required',
                    'string',
                    'max:500',
                ],
                'evaluation' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ],
            ActionTypeEnum::ASSESSMENT_CENTER => [
                'date' => [
                    'required',
                    'string',
                    'date_format:Y-m-d',
                ],
                'timeStart' => [
                    'required',
                    'string',
                    'date_format:H:i',
                ],
                'timeEnd' => [
                    'required',
                    'string',
                    'date_format:H:i',
                ],
                'place' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'instructions' => [
                    'required',
                    'string',
                    'max:500',
                ],
                'noShow' => [
                    'boolean',
                ],
                'evaluation' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ],
            ActionTypeEnum::REJECTION => [
                'rejectedByCandidate' => [
                    'boolean',
                ],
                'rejectionReason' => [
                    Rule::excludeIf($this->boolean('rejectedByCandidate') === true),
                    'required',
                    'string',
                ],
                'refusalReason' => [
                    Rule::excludeIf($this->boolean('rejectedByCandidate') === false),
                    'required',
                    'string',
                ],
            ],
            ActionTypeEnum::CUSTOM => [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ],
            ActionTypeEnum::OFFER => [
                'offerJobTitle' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'offerCompany' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'offerEmploymentForms' => [
                    'required',
                    'array',
                ],
                'offerEmploymentForms.*' => [
                    'required',
                    'string',
                ],
                'offerPlace' => [
                    Rule::excludeIf(!in_array('on_site', $this->input('offerEmploymentForms', []))),
                    'required',
                    'string',
                    'max:255',
                ],
                'offerSalary' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'offerSalaryCurrency' => [
                    'required',
                    'string',
                ],
                'offerSalaryFrequency' => [
                    'required',
                    'string',
                ],
                'offerWorkload' => [
                    'required',
                    'string',
                ],
                'offerEmploymentRelationship' => [
                    'required',
                    'string',
                ],
                'offerStartDate' => [
                    'required',
                    'string',
                    'date_format:Y-m-d',
                ],
                'offerEmploymentDuration' => [
                    'required',
                    'string',
                ],
                'offerCertainPeriodTo' => [
                    Rule::excludeIf($this->input('offerEmploymentDuration') !== 'certain'),
                    'required',
                    'string',
                    'date_format:Y-m-d',
                ],
                'offerTrialPeriod' => [
                    'required',
                    'integer',
                    'min:0',
                ],
                'offerCandidateNote' => [
                    'nullable',
                    'string',
                    'max:500',
                ],
            ],
            ActionTypeEnum::COMMUNICATION => [],
        };

        return array_merge($actionFields, [
            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);
    }

    public function toData(): ActionData
    {
        $type = $this->enum('type', ActionTypeEnum::class);

        return match ($type) {
            ActionTypeEnum::INTERVIEW => new ActionData(
                type: $type,
                date: $this->date('date', 'Y-m-d'),
                timeStart: $this->date('timeStart', 'H:i'),
                timeEnd: $this->date('timeEnd', 'H:i'),
                place: $this->input('interviewForm') === 'personal' ? (string) $this->input('place') : null,
                interviewForm: (string) $this->input('interviewForm'),
                interviewType: (string) $this->input('interviewType'),
                unavailable: $this->input('interviewType') === 'screening' ? $this->boolean('unavailable') : null,
                noShow: $this->input('interviewType') !== 'screening' ? $this->boolean('noShow') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::TEST => new ActionData(
                type: $type,
                instructions: (string) $this->input('instructions'),
                evaluation: $this->filled('evaluation') ? (string) $this->input('evaluation') : null,
                testType: (string) $this->input('testType'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::TASK => new ActionData(
                type: $type,
                date: $this->filled('date') ? $this->date('date', 'Y-m-d') : null,
                timeEnd: $this->filled('timeEnd') ? $this->date('timeEnd', 'H:i') : null,
                instructions: (string) $this->input('instructions'),
                evaluation: $this->filled('evaluation') ? (string) $this->input('evaluation') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::ASSESSMENT_CENTER => new ActionData(
                type: $type,
                date: $this->date('date', 'Y-m-d'),
                timeStart: $this->date('timeStart', 'H:i'),
                timeEnd: $this->date('timeEnd', 'H:i'),
                place: (string) $this->input('place'),
                instructions: (string) $this->input('instructions'),
                evaluation: $this->filled('evaluation') ? (string) $this->input('evaluation') : null,
                noShow: $this->boolean('noShow'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::COMMUNICATION => new ActionData(
                type: $type,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::CUSTOM => new ActionData(
                type: $type,
                name: (string) $this->input('name'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::OFFER => new ActionData(
                type: $type,
                offerJobTitle: (string) $this->input('offerJobTitle'),
                offerCompany: (string) $this->input('offerCompany'),
                offerEmploymentForms: (array) $this->input('offerEmploymentForms'),
                offerPlace: in_array('on_site', (array) $this->input('offerEmploymentForms')) ? (string) $this->input('offerPlace') : null,
                offerSalary: (int) $this->input('offerSalary'),
                offerSalaryCurrency: (string) $this->input('offerSalaryCurrency'),
                offerSalaryFrequency: (string) $this->input('offerSalaryFrequency'),
                offerWorkload: (string) $this->input('offerWorkload'),
                offerEmploymentRelationship: (string) $this->input('offerEmploymentRelationship'),
                offerStartDate: $this->date('offerStartDate', 'Y-m-d'),
                offerEmploymentDuration: (string) $this->input('offerEmploymentDuration'),
                offerCertainPeriodTo: $this->input('offerEmploymentDuration') === 'certain' ? $this->date('offerCertainPeriodTo', 'Y-m-d') : null,
                offerTrialPeriod: (int) $this->input('offerTrialPeriod'),
                offerCandidateNote: $this->filled('offerCandidateNote') ? (string) $this->input('offerCandidateNote') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::REJECTION => new ActionData(
                type: $type,
                rejectedByCandidate: $this->boolean('rejectedByCandidate'),
                rejectionReason: $this->boolean('rejectedByCandidate') === false ? (string) $this->input('rejectionReason') : null,
                refusalReason: $this->boolean('rejectedByCandidate') === true ? (string) $this->input('refusalReason') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
        };
    }
}
