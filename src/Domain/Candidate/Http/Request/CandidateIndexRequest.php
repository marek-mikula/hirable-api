<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request;

use App\Http\Requests\Request;
use Support\Grid\Traits\AsGridRequest;

class CandidateIndexRequest extends Request
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
