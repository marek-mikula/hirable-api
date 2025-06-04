<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Domain\Position\Enums\PositionApprovalStateEnum;
use Spatie\LaravelData\Data;

class PositionApprovalDecideData extends Data
{
    public PositionApprovalStateEnum $state;

    public ?string $note;
}
