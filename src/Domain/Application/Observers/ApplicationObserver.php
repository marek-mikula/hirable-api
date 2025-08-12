<?php

declare(strict_types=1);

namespace Domain\Application\Observers;

use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Models\Application;

class ApplicationObserver
{
    public function created(Application $application): void
    {
        ApplicationCreatedEvent::dispatch($application);
    }

    public function updated(Application $application): void
    {
        //
    }

    public function deleted(Application $application): void
    {
        //
    }

    public function restored(Application $application): void
    {
        //
    }

    public function forceDeleted(Application $application): void
    {
        //
    }
}
