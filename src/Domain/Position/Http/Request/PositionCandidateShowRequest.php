<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionCandidatePolicy;

class PositionCandidateShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidatePolicy::show() */
        return $this->user()->can('show', [$this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
