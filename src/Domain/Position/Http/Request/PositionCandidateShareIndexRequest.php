<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Policies\PositionCandidateSharePolicy;

class PositionCandidateShareIndexRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateSharePolicy::index() */
        return $this->user()->can('index', [PositionCandidateShare::class, $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
