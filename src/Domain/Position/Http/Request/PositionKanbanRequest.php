<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;

class PositionKanbanRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionPolicy::showKanban() */
        return $this->user()->can('showKanban', $this->route('position'));
    }

    public function rules(): array
    {
        return [];
    }
}
