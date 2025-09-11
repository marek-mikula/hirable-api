<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionCandidateEvaluationPolicy;

class PositionCandidateEvaluationDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateEvaluationPolicy::delete() */
        return $this->user()->can('delete', [$this->route('positionCandidateEvaluation'), $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
