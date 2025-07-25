<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Rules\Rule;
use Domain\Position\Enums\PositionProcessStepEnum;
use Domain\Position\Http\Request\Data\PositionProcessStepData;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Policies\PositionProcessStepPolicy;

class PositionProcessStepStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::store() */
        return $this->user()->can('store', PositionProcessStep::class);
    }

    public function rules(): array
    {
        return [
            'step' => [
                'required',
                'string',
                'max:50',
                Rule::notIn(collect(PositionProcessStepEnum::cases())->pluck('value')->all())
            ],
        ];
    }

    public function toData(): PositionProcessStepData
    {
        return PositionProcessStepData::from([
            'step' => (string) $this->input('step'),
        ]);
    }
}
