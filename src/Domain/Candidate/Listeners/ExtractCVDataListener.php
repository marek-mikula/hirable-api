<?php

declare(strict_types=1);

namespace Domain\Candidate\Listeners;

use App\Listeners\QueuedListener;
use Domain\Candidate\Events\CandidateCreatedEvent;
use Domain\Candidate\UseCases\ExtractCVDataUseCase;

class ExtractCVDataListener extends QueuedListener
{
    public function handle(CandidateCreatedEvent $event): void
    {
        ExtractCVDataUseCase::make()->handle($event->candidate);
    }
}
