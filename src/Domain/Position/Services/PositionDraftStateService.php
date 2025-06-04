<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Enums\PositionOperationEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Http\Request\Data\PositionData;
use Domain\Position\Models\Position;

class PositionDraftStateService
{
    public function getState(?Position $position, PositionData $data): PositionStateEnum
    {
        throw_if($position && $position->state !== PositionStateEnum::DRAFT, new \Exception('Position needs to be in draft.'));

        if (!$position) {
            return $data->operation === PositionOperationEnum::OPEN ? PositionStateEnum::OPENED : PositionStateEnum::DRAFT;
        }

        return $data->operation === PositionOperationEnum::OPEN ? PositionStateEnum::OPENED : $position->state;
    }

    public function getApprovalState(?Position $position, PositionData $data): ?PositionApprovalStateEnum
    {
        throw_if($position && $position->state !== PositionStateEnum::DRAFT, new \Exception('Position needs to be in draft.'));

        if (!$position) {
            return $data->operation === PositionOperationEnum::SEND_FOR_APPROVAL ? PositionApprovalStateEnum::PENDING : null;
        }

        if ($position->approval_state === null) {
            return $data->operation === PositionOperationEnum::SEND_FOR_APPROVAL ? PositionApprovalStateEnum::PENDING : null;
        }

        if ($position->approval_state === PositionApprovalStateEnum::APPROVED) {
            return PositionApprovalStateEnum::APPROVED;
        }

        return match ($data->operation) {
            PositionOperationEnum::SEND_FOR_APPROVAL => PositionApprovalStateEnum::PENDING,
            PositionOperationEnum::SAVE => $position->approval_state,
            PositionOperationEnum::OPEN => null,
        };
    }
}
