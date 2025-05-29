<?php

declare(strict_types=1);

namespace Support\Grid\Grids;

use Domain\User\Models\User;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Enums\GridEnum;

class CompanyUserGrid implements Grid
{
    public function getDefinition(User $user): GridDefinition
    {
        return new GridDefinition(
            identifier: GridEnum::COMPANY_USER,
            keyAttribute: 'id',
            columns: [
                new GridColumnDefinition(
                    key: 'id',
                    label: 'model.common.id',
                    allowToggle: false,
                ),
                new GridColumnDefinition(
                    key: 'role',
                    label: 'model.common.role',
                ),
                new GridColumnDefinition(
                    key: 'firstname',
                    label: 'model.common.firstname',
                ),
                new GridColumnDefinition(
                    key: 'lastname',
                    label: 'model.common.lastname',
                ),
                new GridColumnDefinition(
                    key: 'email',
                    label: 'model.common.email',
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
