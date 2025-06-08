<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionPolicy;

class PositionDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::delete() */
        return $this->user()->can('delete', $this->route('position'));
    }

    public function rules(): array
    {
        return [];
    }
}
