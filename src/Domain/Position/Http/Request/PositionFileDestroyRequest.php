<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Support\File\Policies\FilePolicy;

class PositionFileDestroyRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see FilePolicy::delete() */
        return $this->user()->can('delete', $this->route('file'));
    }

    public function rules(): array
    {
        return [];
    }
}
