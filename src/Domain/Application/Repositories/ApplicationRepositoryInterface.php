<?php

declare(strict_types=1);

namespace Domain\Application\Repositories;

use Domain\Application\Models\Application;
use Domain\Application\Repositories\Input\ApplicationStoreInput;
use Domain\Application\Repositories\Input\ApplicationUpdateInput;

interface ApplicationRepositoryInterface
{
    public function store(ApplicationStoreInput $input): Application;

    public function update(Application $application, ApplicationUpdateInput $input): Application;

    public function setProcessed(Application $application): Application;
}
