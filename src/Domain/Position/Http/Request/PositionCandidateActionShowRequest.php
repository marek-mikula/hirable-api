<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionCandidateActionPolicy;

class PositionCandidateActionShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateActionPolicy::show() */
        return $this->user()->can('show', [
            $this->route('positionCandidateAction'),
            $this->route('positionCandidate'),
            $this->route('position')
        ]);
    }

    public function rules(): array
    {
        return [];
    }
}
