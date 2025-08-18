<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Candidate\Policies\CandidatePolicy;

class CandidateShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see CandidatePolicy::show() */
        return $this->user()->can('show', $this->route('candidate'));
    }

    public function rules(): array
    {
        return [];
    }
}
