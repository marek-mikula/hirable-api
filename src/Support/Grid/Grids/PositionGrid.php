<?php

declare(strict_types=1);

namespace Support\Grid\Grids;

use Domain\User\Models\User;
use Support\Grid\Contracts\Grid;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Enums\GridEnum;

class PositionGrid implements Grid
{
    public function getDefinition(User $user): GridDefinition
    {
        return new GridDefinition(
            identifier: GridEnum::POSITION,
            keyAttribute: 'id',
            columns: [
                new GridColumnDefinition(
                    key: 'id',
                    label: 'model.common.id',
                    allowToggle: false,
                ),
                new GridColumnDefinition(
                    key: 'name',
                    label: 'model.common.title',
                ),
                new GridColumnDefinition(
                    key: 'department',
                    label: 'model.position.department',
                ),
                new GridColumnDefinition(
                    key: 'state',
                    label: 'model.common.state',
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
