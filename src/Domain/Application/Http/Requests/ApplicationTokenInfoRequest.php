<?php

declare(strict_types=1);

namespace Domain\Application\Http\Requests;

use App\Http\Requests\Request;

class ApplicationTokenInfoRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
