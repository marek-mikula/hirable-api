<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use Domain\Application\Models\Application;
use Domain\Application\Repositories\Input\ApplicationStoreInput;

interface ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application;
}
