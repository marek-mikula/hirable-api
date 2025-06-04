<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;

class PositionDraftValidationService
{
    public function validate(?Position $position, PositionData $data): void
    {
        if ($data->operation === PositionOperationEnum::OPEN && $data->hasAnyApprovers()) {
            throw new \Exception('Cannot open position with approvers.');
        }
    }
}
