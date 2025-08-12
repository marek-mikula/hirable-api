<?php

declare(strict_types=1);

namespace Domain\Position\Repositories\Inputs;

use Domain\Position\Enums\PositionApprovalStateEnum;

readonly class PositionApprovalDecideInput
{
    public function __construct(
        public PositionApprovalStateEnum $state,
        public ?string $note,
    ) {
    }
}
