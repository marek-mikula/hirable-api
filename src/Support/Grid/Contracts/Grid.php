<?php

namespace Support\Grid\Contracts;

use App\Models\User;
use Support\Grid\Data\Definition\GridDefinition;

interface Grid
{
    public function getDefinition(User $user): GridDefinition;
}
