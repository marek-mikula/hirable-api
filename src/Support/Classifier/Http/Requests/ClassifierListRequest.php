<?php

declare(strict_types=1);

namespace Support\Classifier\Http\Requests;

use App\Http\Requests\AuthRequest;

class ClassifierListRequest extends AuthRequest
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
