<?php

namespace Support\Grid\Enums;

use Support\Grid\Grids\CandidateGrid;
use Support\Setting\Enums\SettingKeyEnum;

enum GridEnum: string
{
    case CANDIDATE = 'candidate';

    public function getClass(): string
    {
        return match ($this) {
            self::CANDIDATE => CandidateGrid::class,
        };
    }

    public function getSettingKey(): SettingKeyEnum
    {
        return match ($this) {
            self::CANDIDATE => SettingKeyEnum::GRID_CANDIDATE,
        };
    }
}
