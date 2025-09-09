<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Carbon\Carbon;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

readonly class PositionCandidateEvaluationRequestData
{
    /**
     * @param Collection<User> $hiringManagers
     */
    public function __construct(
        public Collection $hiringManagers,
        public ?Carbon $fillUntil,
    ) {
    }
}
