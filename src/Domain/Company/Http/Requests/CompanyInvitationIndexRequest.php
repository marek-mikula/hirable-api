<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests;

use App\Http\Requests\Request;
use Support\Grid\Traits\AsGridRequest;

class CompanyInvitationIndexRequest extends Request
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
