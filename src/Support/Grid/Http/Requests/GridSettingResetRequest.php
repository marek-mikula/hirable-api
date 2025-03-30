<?php

namespace Support\Grid\Http\Requests;

use App\Http\Requests\AuthRequest;

class GridSettingResetRequest extends AuthRequest
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
