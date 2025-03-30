<?php

namespace Support\Grid\Enums;

use Support\Grid\Grids\UserGrid;
use Support\Setting\Enums\SettingKeyEnum;

enum GridEnum: string
{
    case USER = 'user';

    public function getClass(): string
    {
        return match ($this) {
            self::USER => UserGrid::class,
        };
    }

    public function getSettingKey(): SettingKeyEnum
    {
        return match ($this) {
            self::USER => SettingKeyEnum::GRID_USERS,
        };
    }
}
