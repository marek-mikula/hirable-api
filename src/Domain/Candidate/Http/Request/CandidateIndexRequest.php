<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request;

use App\Http\Requests\AuthRequest;
use Support\Grid\Concerns\AsGridRequest;

class CandidateIndexRequest extends AuthRequest
{
    use AsGridRequest;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
