<?php

declare(strict_types=1);

namespace Support\Grid\Enums;

use Support\Grid\Grids\CandidateGrid;
use Support\Grid\Grids\CompanyInvitationGrid;
use Support\Grid\Grids\CompanyUserGrid;
use Support\Grid\Grids\PositionGrid;
use Support\Setting\Enums\SettingKeyEnum;

enum GridEnum: string
{
    case CANDIDATE = 'candidate';
    case COMPANY_INVITATION = 'company-invitation';
    case COMPANY_USER = 'company-user';
    case POSITION = 'position';

    public function getClass(): string
    {
        return match ($this) {
            self::CANDIDATE => CandidateGrid::class,
            self::COMPANY_INVITATION => CompanyInvitationGrid::class,
            self::COMPANY_USER => CompanyUserGrid::class,
            self::POSITION => PositionGrid::class,
        };
    }

    public function getSettingKey(): SettingKeyEnum
    {
        return match ($this) {
            self::CANDIDATE => SettingKeyEnum::GRID_CANDIDATE,
            self::COMPANY_INVITATION => SettingKeyEnum::GRID_COMPANY_INVITATION,
            self::COMPANY_USER => SettingKeyEnum::GRID_COMPANY_USER,
            self::POSITION => SettingKeyEnum::GRID_POSITION,
        };
    }
}
