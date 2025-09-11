<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Domain\Position\Policies\PositionCandidateEvaluationPolicy;

class PositionCandidateEvaluationUpdateRequest extends PositionCandidateEvaluationStoreRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateEvaluationPolicy::update() */
        return $this->user()->can('update', [$this->route('positionCandidateEvaluation'), $this->route('positionCandidate'), $this->route('position')]);
    }
}
