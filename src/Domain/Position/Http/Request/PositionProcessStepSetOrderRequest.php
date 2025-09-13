<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use Domain\Position\Policies\PositionPolicy;

class PositionProcessStepSetOrderRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function authorize(): bool
    {
        /** @see PositionPolicy::setProcessStepOrder() */
        return $this->user()->can('setProcessStepOrder', $this->route('position'));
    }

    public function rules(): array
    {
        return [
            'order' => [
                'required',
                'array',
            ],
            'order.*' => [
                'required',
                'integer',
            ],
        ];
    }

    /**
     * @return int[]
     */
    public function getOrder(): array
    {
        return $this->collect('order')->map(fn (mixed $value): int => (int) $value)->values()->all();
    }
}
