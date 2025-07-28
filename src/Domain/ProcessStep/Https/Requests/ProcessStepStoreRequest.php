<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Https\Requests;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\ProcessStep\Enums\ProcessStepEnum;
use Domain\ProcessStep\Https\Requests\Data\ProcessStepData;
use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Policies\ProcessStepPolicy;

class ProcessStepStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see ProcessStepPolicy::store() */
        return $this->user()->can('store', ProcessStep::class);
    }

    public function rules(): array
    {
        return [
            'step' => [
                'required',
                'string',
                'max:50',
                Rule::notIn(collect(ProcessStepEnum::cases())->pluck('value')->all()),
                Rule::unique(ProcessStep::class, 'step')->where('company_id', $this->user()->company_id),
            ],
            'isRepeatable' => [
                'boolean',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'step' => __('model.processStep.step'),
            'isRepeatable' => __('model.processStep.isRepeatable'),
        ];
    }

    public function toData(): ProcessStepData
    {
        return ProcessStepData::from([
            'step' => (string) $this->input('step'),
            'isRepeatable' => $this->boolean('isRepeatable'),
        ]);
    }
}
