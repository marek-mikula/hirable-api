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
        return $this->user()->can('store', [PositionCandidateAction::class, $this->route('positionCandidate'), $this->route('position')]);
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
                'interviewForm' => [
                    'required',
                    'string',
                ],
                'interviewType' => [
                    'required',
                    'string',
                ],
                'place' => [
                    'nullable',
                    Rule::requiredIf($this->input('interviewForm') === 'personal'),
                    'string',
                    'max:255',
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
                'result' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            ActionTypeEnum::TASK => [
                'instructions' => [
                    'required',
                    'string',
                    'max:500',
                ],
                'result' => [
                    'nullable',
                    'string',
                    'max:255',
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
                'result' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            ActionTypeEnum::REJECTION => [
                'rejectionReason' => [
                    'required',
                    'string',
                ],
            ],
            ActionTypeEnum::REFUSAL => [
                'refusalReason' => [
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
            ActionTypeEnum::OFFER, ActionTypeEnum::COMMUNICATION => [],
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
                interviewForm: (string) $this->input('interviewForm'),
                interviewType: (string) $this->input('interviewType'),
                place: $this->filled('place') ? (string) $this->input('place') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::TEST => new ActionData(
                type: $type,
                testType: (string) $this->input('testType'),
                instructions: (string) $this->input('instructions'),
                result: $this->filled('result') ? (string) $this->input('result') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::TASK => new ActionData(
                type: $type,
                instructions: (string) $this->input('instructions'),
                result: $this->filled('result') ? (string) $this->input('result') : null,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::ASSESSMENT_CENTER => new ActionData(
                type: $type,
                date: $this->date('date', 'Y-m-d'),
                timeStart: $this->date('timeStart', 'H:i'),
                timeEnd: $this->date('timeEnd', 'H:i'),
                interviewForm: (string) $this->input('interviewForm'),
                interviewType: (string) $this->input('interviewType'),
                place: (string) $this->input('place'),
                instructions: (string) $this->input('instructions'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::REJECTION => new ActionData(
                type: $type,
                rejectionReason: (string) $this->input('rejectionReason'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::REFUSAL => new ActionData(
                type: $type,
                refusalReason: (string) $this->input('refusalReason'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::CUSTOM => new ActionData(
                type: $type,
                name: (string) $this->input('name'),
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
            ActionTypeEnum::OFFER, ActionTypeEnum::COMMUNICATION => new ActionData(
                type: $type,
                note: $this->filled('note') ? (string) $this->input('note') : null,
            ),
        };
    }
}
