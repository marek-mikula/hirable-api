<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\QueuedListener;
use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\UseCases\ProcessApplicationUseCase;

class ProcessApplicationListener extends QueuedListener
{
    public function handle(ApplicationCreatedEvent $event): void
    {
        ProcessApplicationUseCase::make()->handle($event->application);
    }
}
