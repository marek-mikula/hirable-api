<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionCandidateActionPolicy;

class PositionCandidateActionDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateActionPolicy::delete() */
        return $this->user()->can('delete', [
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
