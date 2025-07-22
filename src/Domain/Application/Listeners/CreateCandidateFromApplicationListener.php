<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\QueuedListener;
use Domain\Application\Events\ApplicationProcessedEvent;
use Domain\Application\UseCases\CreateCandidateFromApplicationUseCase;

class CreateCandidateFromApplicationListener extends QueuedListener
{
    public function handle(ApplicationProcessedEvent $event): void
    {
        CreateCandidateFromApplicationUseCase::make()->handle($event->application);
    }
}
