<?php

namespace Support\Grid\Grids;

use App\Models\User;
use Support\Grid\Contracts\Grid;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Enums\GridEnum;

class CompanyInvitationGrid implements Grid
{
    public function getDefinition(User $user): GridDefinition
    {
        return new GridDefinition(
            identifier: GridEnum::COMPANY_INVITATION,
            keyAttribute: 'id',
            columns: [
                new GridColumnDefinition(
                    key: 'id',
                    label: 'model.common.id',
                    allowToggle: false,
                ),
                new GridColumnDefinition(
                    key: 'state',
                    label: 'model.common.state',
                    allowToggle: false,
                ),
                new GridColumnDefinition(
                    key: 'email',
                    label: 'model.common.email',
                ),
                new GridColumnDefinition(
                    key: 'role',
                    label: 'model.common.role',
                ),
                new GridColumnDefinition(
                    key: 'validUntil',
                    label: 'model.token.validUntil',
                ),
                new GridColumnDefinition(
                    key: 'createdAt',
                    label: 'model.common.createdAt',
                ),
            ],
            actions: [],
            allowSearch: true,
            allowSettings: true,
            allowFilter: false,
        );
    }
}
