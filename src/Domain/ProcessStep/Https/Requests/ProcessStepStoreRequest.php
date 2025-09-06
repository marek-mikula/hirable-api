<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\ProcessStep\Enums\StepEnum;
use Domain\ProcessStep\Https\Requests\Data\ProcessStepData;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Policies\ProcessStepPolicy;
use Domain\ProcessStep\Services\ProcessStepActionService;

class ProcessStepStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see ProcessStepPolicy::store() */
        return $this->user()->can('store', ProcessStep::class);
    }

    public function rules(ProcessStepActionService $processStepActionService): array
    {
        return [
            'step' => [
                'required',
                'string',
                'max:50',
                Rule::notIn(collect(StepEnum::cases())->pluck('value')->all()),
                Rule::unique(ProcessStep::class, 'step')->where('company_id', $this->user()->company_id),
            ],
            'isRepeatable' => [
                'boolean',
            ],
            'triggersAction' => [
                'nullable',
                'string',
                Rule::enum(ActionTypeEnum::class)->only($processStepActionService->getTriggerableAction()),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'step' => __('model.process_step.step'),
            'isRepeatable' => __('model.process_step.is_repeatable'),
            'triggersAction' => __('model.process_step.triggers_action'),
        ];
    }

    public function toData(): ProcessStepData
    {
        return new ProcessStepData(
            step: (string) $this->input('step'),
            isRepeatable: $this->boolean('isRepeatable'),
            triggersAction: $this->filled('triggersAction') ? $this->enum('triggersAction', ActionTypeEnum::class) : null,
        );
    }
}
