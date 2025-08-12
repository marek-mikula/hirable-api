<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionProcessStepPolicy;

class PositionProcessStepDeleteRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::delete() */
        return $this->user()->can('delete', [$this->route('positionProcessStep'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
