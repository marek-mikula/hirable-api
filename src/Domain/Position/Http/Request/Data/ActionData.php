<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\Position\Enums\ActionTypeEnum;

readonly class ActionData
{
    public function __construct(
        public ActionTypeEnum $action,
        public ?Carbon $date = null,
        public ?Carbon $timeStart = null,
        public ?Carbon $timeEnd = null,
        public ?string $interviewForm = null,
        public ?string $interviewType = null,
        public ?string $place = null,
        public ?string $testType = null,
        public ?string $instructions = null,
        public ?string $result = null,
        public ?string $rejectionReason = null,
        public ?string $refusalReason = null,
        public ?string $name = null,
        public ?string $note = null,
    ) {
    }
}
