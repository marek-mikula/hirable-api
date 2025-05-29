<?php

declare(strict_types=1);

namespace Support\Grid\Grids;

use Domain\User\Models\User;
use Support\Grid\Data\Definition\GridDefinition;

interface Grid
{
    public function getDefinition(User $user): GridDefinition;
}
