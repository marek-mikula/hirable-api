<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Traits\ValidationFailsWithStatus;
use Domain\Position\Http\Request\Data\KanbanSettingsData;
use Domain\Position\Policies\PositionPolicy;

class PositionKanbanSettingsRequest extends AuthRequest
{
    use ValidationFailsWithStatus;

    public function authorize(): bool
    {
        /** @see PositionPolicy::updateKanbanSettings() */
        return $this->user()->can('updateKanbanSettings', $this->route('position'));
    }

    public function rules(): array
    {
        return [
            'order' => [
                'required',
                'array',
            ],
            'order.*' => [
                'required',
                'string',
            ],
        ];
    }

    public function toData(): KanbanSettingsData
    {
        return new KanbanSettingsData(
            order: $this->collect('order')
                ->map(fn (mixed $value) => (string) $value)
                ->values()
                ->all()
        );
    }
}
