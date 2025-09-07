<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionCandidateSharePolicy;

class PositionCandidateShareDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateSharePolicy::delete() */
        return $this->user()->can('delete', [
            $this->route('positionCandidateShare'),
            $this->route('positionCandidate'),
            $this->route('position')
        ]);
    }

    public function rules(): array
    {
        return [];
    }
}
