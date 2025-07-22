<?php

declare(strict_types=1);

namespace Domain\Application\Listeners;

use App\Listeners\Listener;
use Domain\Application\Events\ApplicationCreatedEvent;
use Domain\Application\Jobs\CreateCandidateFromApplicationJob;
use Domain\Application\Jobs\ScoreApplicationJob;
use Domain\Application\Jobs\SetApplicationProcessedJob;
use Illuminate\Support\Facades\Bus;

class ProcessApplicationListener extends Listener
{
    public function handle(ApplicationCreatedEvent $event): void
    {
        Bus::chain([
            new ScoreApplicationJob($event->application),
            new CreateCandidateFromApplicationJob($event->application),
            new SetApplicationProcessedJob($event->application),
        ])->dispatch();
    }
}
