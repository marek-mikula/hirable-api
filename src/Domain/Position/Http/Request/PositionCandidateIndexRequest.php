<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Policies\PositionCandidatePolicy;

class PositionCandidateIndexRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidatePolicy::index() */
        return $this->user()->can('index', [PositionCandidate::class, $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
