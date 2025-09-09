<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Policies\PositionCandidateEvaluationPolicy;

class PositionCandidateEvaluationIndexRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateEvaluationPolicy::index() */
        return $this->user()->can('index', [PositionCandidateEvaluation::class, $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
