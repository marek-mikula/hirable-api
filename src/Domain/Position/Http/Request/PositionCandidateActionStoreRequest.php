<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Enums\OfferStateEnum;
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

    protected function getType(): ActionTypeEnum
    {
        return $this->enum('type', ActionTypeEnum::class);
    }

    public function rules(): array
    {
        $actionFields = match ($this->getType()) {
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
                    'string',
                    'date_format:H:i',
                ],
                'taskType' => [
                    'required',
                    'string',
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
                'offerState' => [
                    'required',
                    'string',
                    Rule::enum(OfferStateEnum::class),
                ],
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
            ],
            ActionTypeEnum::START_OF_WORK => [
                'realStartDate' => [
                    'required',
                    'string',
                    'date_format:Y-m-d',
                ]
            ],
        };

        return array_merge($actionFields, [
            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);
    }

    public function attributes(): array
    {
        return [
            'date' => __('model.position_candidate_action.date'),
            'timeStart' => __('model.position_candidate_action.timeStart'),
            'timeEnd' => __('model.position_candidate_action.timeEnd'),
            'place' => __('model.position_candidate_action.place'),
            'evaluation' => __('model.position_candidate_action.evaluation'),
            'name' => __('model.position_candidate_action.name'),
            'interviewForm' => __('model.position_candidate_action.interviewForm'),
            'interviewType' => __('model.position_candidate_action.interviewType'),
            'rejectedByCandidate' => __('model.position_candidate_action.rejectedByCandidate'),
            'reason' => __('model.position_candidate_action.reason'),
            'taskType' => __('model.position_candidate_action.taskType'),
            'offerState' => __('model.position_candidate_action.offerState'),
            'offerJobTitle' => __('model.position_candidate_action.offerJobTitle'),
            'offerCompany' => __('model.position_candidate_action.offerCompany'),
            'offerEmploymentForms' => __('model.position_candidate_action.offerEmploymentForms'),
            'offerPlace' => __('model.position_candidate_action.offerPlace'),
            'offerSalary' => __('model.position_candidate_action.offerSalary'),
            'offerSalaryCurrency' => __('model.position_candidate_action.offerSalaryCurrency'),
            'offerSalaryFrequency' => __('model.position_candidate_action.offerSalaryFrequency'),
            'offerWorkload' => __('model.position_candidate_action.offerWorkload'),
            'offerEmploymentRelationship' => __('model.position_candidate_action.offerEmploymentRelationship'),
            'offerStartDate' => __('model.position_candidate_action.offerStartDate'),
            'offerEmploymentDuration' => __('model.position_candidate_action.offerEmploymentDuration'),
            'offerCertainPeriodTo' => __('model.position_candidate_action.offerCertainPeriodTo'),
            'offerTrialPeriod' => __('model.position_candidate_action.offerTrialPeriod'),
            'realStartDate' => __('model.position_candidate_action.realStartDate'),
            'note' => __('model.position_candidate_action.note'),
        ];
    }

    public function toData(): ActionData
    {
        $type = $this->getType();

        return match ($type) {
            ActionTypeEnum::INTERVIEW => new ActionData(
                type: $type,
                date: $this->date('date', 'Y-m-d'),
                timeStart: $this->date('timeStart', 'H:i'),
                timeEnd: $this->date('timeEnd', 'H:i'),
                place: $this->input('interviewForm') === 'personal' ? (string) $this->input('place') : null,
                interviewForm: (string) $this->input('interviewForm'),
                interviewType: (string) $this->input('interviewType'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::TASK => new ActionData(
                type: $type,
                date: $this->filled('date') ? $this->date('date', 'Y-m-d') : null,
                timeEnd: $this->filled('timeEnd') ? $this->date('timeEnd', 'H:i') : null,
                evaluation: $this->filled('evaluation') ? (string) $this->input('evaluation') : null,
                taskType: (string) $this->input('taskType'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::ASSESSMENT_CENTER => new ActionData(
                type: $type,
                date: $this->date('date', 'Y-m-d'),
                timeStart: $this->date('timeStart', 'H:i'),
                timeEnd: $this->date('timeEnd', 'H:i'),
                place: (string) $this->input('place'),
                evaluation: $this->filled('evaluation') ? (string) $this->input('evaluation') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::CUSTOM => new ActionData(
                type: $type,
                name: (string) $this->input('name'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::OFFER => new ActionData(
                type: $type,
                offerState: $this->enum('offerState', OfferStateEnum::class),
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
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::REJECTION => new ActionData(
                type: $type,
                rejectedByCandidate: $this->boolean('rejectedByCandidate'),
                rejectionReason: $this->boolean('rejectedByCandidate') === false ? (string) $this->input('rejectionReason') : null,
                refusalReason: $this->boolean('rejectedByCandidate') === true ? (string) $this->input('refusalReason') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::START_OF_WORK => new ActionData(
                type: $type,
                realStartDate: $this->date('realStartDate', 'Y-m-d'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
        };
    }
}
