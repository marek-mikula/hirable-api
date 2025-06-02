<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Listeners\ContinueApprovalProcessListener;
use Domain\Position\Listeners\RejectApprovalProcessListener;
use Domain\Position\Listeners\SendApprovedNotificationsListener;
use Domain\Position\Listeners\SendRejectedNotificationsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PositionApprovedEvent::class => [
            ContinueApprovalProcessListener::class,
            SendApprovedNotificationsListener::class,
        ],
        PositionRejectedEvent::class => [
            RejectApprovalProcessListener::class,
            SendRejectedNotificationsListener::class,
        ]
    ];
}
