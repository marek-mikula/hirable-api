<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use App\Rules\Rule;
use Domain\Position\Enums\PositionCandidatePriorityEnum;
use Domain\Position\Policies\PositionCandidatePolicy;

class PositionCandidateSetPriorityRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function authorize(): bool
    {
        /** @see PositionCandidatePolicy::setPriority() */
        return $this->user()->can('setPriority', [$this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [
            'priority' => [
                'required',
                'integer',
                Rule::enum(PositionCandidatePriorityEnum::class),
            ],
        ];
    }

    public function getPriority(): PositionCandidatePriorityEnum
    {
        return $this->enum('priority', PositionCandidatePriorityEnum::class);
    }
}
