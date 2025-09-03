<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Policies\PositionProcessStepPolicy;

class PositionProcessStepIndexRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionProcessStepPolicy::index() */
        return $this->user()->can('index', [PositionProcessStep::class, $this->route('position')]);
    }

    public function rules(): array
    {
        return [];
    }
}
