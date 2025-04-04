<?php

namespace Support\Grid\Enums;

use Support\Grid\Grids\CandidateGrid;
use Support\Grid\Grids\CompanyInvitationGrid;
use Support\Setting\Enums\SettingKeyEnum;

enum GridEnum: string
{
    case CANDIDATE = 'candidate';
    case COMPANY_INVITATION = 'company-invitation';

    public function getClass(): string
    {
        return match ($this) {
            self::CANDIDATE => CandidateGrid::class,
            self::COMPANY_INVITATION => CompanyInvitationGrid::class,
        };
    }

    public function getSettingKey(): SettingKeyEnum
    {
        return match ($this) {
            self::CANDIDATE => SettingKeyEnum::GRID_CANDIDATE,
            self::COMPANY_INVITATION => SettingKeyEnum::GRID_COMPANY_INVITATION,
        };
    }
}
