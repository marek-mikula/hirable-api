<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionPolicy;

class PositionShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::show() */
        return $this->user()->can('show', $this->route('position'));
    }

    public function rules(): array
    {
        return [];
    }
}
