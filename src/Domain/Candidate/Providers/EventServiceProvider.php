<?php

declare(strict_types=1);

namespace Domain\Candidate\Providers;

use Domain\Candidate\Events\CandidateCreatedEvent;
use Domain\Candidate\Listeners\ExtractCVDataListener;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Observers\CandidateObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CandidateCreatedEvent::class => [
            ExtractCVDataListener::class,
        ],
    ];

    protected $observers = [
        Candidate::class => CandidateObserver::class
    ];
}
