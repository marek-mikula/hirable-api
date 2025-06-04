<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\AuthRequest;
use Support\Grid\Traits\AsGridRequest;

class CompanyInvitationIndexRequest extends AuthRequest
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
