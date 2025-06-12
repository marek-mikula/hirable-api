<?php

declare(strict_types=1);

namespace Support\File\Http\Requests;

use App\Http\Requests\AuthRequest;
use Support\File\Policies\FilePolicy;

class FileShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see FilePolicy::show() */
        return $this->user()->can('show', $this->route('file'));
    }

    public function rules(): array
    {
        return [];
    }
}
