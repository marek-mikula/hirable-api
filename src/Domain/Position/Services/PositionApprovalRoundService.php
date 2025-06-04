<?php

declare(strict_types=1);

namespace Domain\Position\Services;

use Domain\Position\Enums\PositionRoleEnum;

class PositionApprovalRoundService
{
    public function getNextRound(?int $currentRound): int
    {
        // normalize the round between 1 and 2
        return (int) min(2, max(0, ($currentRound ?? 0) + 1));
    }

    public function getPreviousRound(?int $currentRound): ?int
    {
        return ($currentRound === null || $currentRound <= 1) ? null : ($currentRound - 1);
    }

    public function hasNextRound(?int $currentRound): bool
    {
        return $this->getNextRound($currentRound) !== $currentRound;
    }

    /**
     * @return PositionRoleEnum[]
     */
    public function getRolesByRound(int $round): array
    {
        return match ($round) {
            1 => [PositionRoleEnum::HIRING_MANAGER],
            2 => [PositionRoleEnum::APPROVER, PositionRoleEnum::EXTERNAL_APPROVER],
        };
    }
}
