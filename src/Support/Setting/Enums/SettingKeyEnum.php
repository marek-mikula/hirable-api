<?php

declare(strict_types=1);

namespace Support\Setting\Enums;

enum SettingKeyEnum: string
{
    case GRID_CANDIDATE = 'grid.candidate';
    case GRID_COMPANY_INVITATION = 'grid.company-invitation';
    case GRID_COMPANY_USER = 'grid.company-user';
    case GRID_POSITION = 'grid.position';
}
