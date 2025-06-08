<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionPolicy;

class PositionDuplicateRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::duplicate() */
        return $this->user()->can('duplicate', $this->route('position'));
    }

    public function rules(): array
    {
        return [];
    }
}
