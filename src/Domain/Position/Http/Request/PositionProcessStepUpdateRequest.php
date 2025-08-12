<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Http\Request\Data\PositionProcessStepData;
use Domain\Position\Policies\PositionProcessStepPolicy;

class PositionProcessStepUpdateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::update() */
        return $this->user()->can('update', [$this->route('positionProcessStep'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [
            'label' => [
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    public function toData(): PositionProcessStepData
    {
        return PositionProcessStepData::from([
            'label' => $this->filled('name') ? (string) $this->input('name') : null
        ]);
    }
}
