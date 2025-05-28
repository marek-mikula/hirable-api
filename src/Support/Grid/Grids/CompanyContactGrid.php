<?php

declare(strict_types=1);

namespace Support\Grid\Grids;

use Domain\User\Models\User;
use Support\Grid\Contracts\Grid;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Enums\GridEnum;

class CompanyContactGrid implements Grid
{
    public function getDefinition(User $user): GridDefinition
    {
        return new GridDefinition(
            identifier: GridEnum::COMPANY_CONTACT,
            keyAttribute: 'id',
            columns: [
                new GridColumnDefinition(
                    key: 'id',
                    label: 'model.common.id',
                    allowToggle: false,
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
                    key: 'companyName',
                    label: 'model.company.name',
                ),
                new GridColumnDefinition(
                    key: 'email',
                    label: 'model.common.email',
                ),
            ],
            actions: [],
            allowSearch: true,
            allowSettings: true,
            allowFilter: false,
        );
    }
}
