<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Policies\PositionProcessStepPolicy;

class PositionProcessStepShowRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::show() */
        return $this->user()->can('show', [$this->route('positionProcessStep'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
