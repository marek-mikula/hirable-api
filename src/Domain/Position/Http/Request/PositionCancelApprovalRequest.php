<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionPolicy;

class PositionCancelApprovalRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::cancelApproval() */
        return $this->user()->can('cancelApproval', $this->route('position'));
    }

    public function rules(): array
    {
        return [];
    }
}
