<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\Listener;
use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Jobs\ExtractDataFromApplicationJob;

class StartApplicationProcessListener extends Listener
{
    public function handle(ApplicationCreatedEvent $event): void
    {
        ExtractDataFromApplicationJob::dispatch($event->application);
    }
}
