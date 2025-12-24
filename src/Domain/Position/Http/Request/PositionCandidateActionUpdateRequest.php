<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Policies\PositionCandidateActionPolicy;

class PositionCandidateActionUpdateRequest extends PositionCandidateActionStoreRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateActionPolicy::update() */
        return $this->user()->can('update', [
            $this->route('positionCandidateAction'),
            $this->route('positionCandidate'),
            $this->route('position')
        ]);
    }

    protected function getType(): ActionTypeEnum
    {
        /** @var PositionCandidateAction $positionCandidateAction */
        $positionCandidateAction = $this->route('positionCandidateAction');

        return $positionCandidateAction->type;
    }
}
