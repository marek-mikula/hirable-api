<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Support\Grid\Traits\AsGridRequest;

class PositionIndexRequest extends AuthRequest
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
