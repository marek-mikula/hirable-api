<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Http\Request\Data\PositionCandidateEvaluationData;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Policies\PositionCandidateEvaluationPolicy;

class PositionCandidateEvaluationStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateEvaluationPolicy::store() */
        return $this->user()->can('store', [PositionCandidateEvaluation::class, $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [
            'evaluation' => [
                'required',
                'string',
                'max:500',
            ],
            'stars' => [
                'required',
                'integer',
                'between:1,5',
            ],
        ];
    }

    public function toData(): PositionCandidateEvaluationData
    {
        return new PositionCandidateEvaluationData(
            evaluation: (string) $this->input('evaluation'),
            stars: (int) $this->input('stars'),
        );
    }
}
