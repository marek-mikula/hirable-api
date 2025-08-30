<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use App\Rules\Rule;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Policies\PositionCandidatePolicy;

class PositionCandidateSetStepRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function authorize(): bool
    {
        /** @see PositionCandidatePolicy::update() */
        return $this->user()->can('update', [$this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        /** @var Position $position */
        $position = $this->route('position');

        return [
            'positionProcessStep' => [
                'required',
                'integer',
                Rule::exists(PositionProcessStep::class, 'id')->where('position_id', $position->id),
            ],
        ];
    }

    public function getPositionProcessStepId(): int
    {
        return (int) $this->input('positionProcessStep');
    }
}
