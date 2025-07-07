<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Events\PositionApprovalEvent;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Listeners\CancelApprovalProcessListener;
use Domain\Position\Listeners\ContinueApprovalProcessListener;
use Domain\Position\Listeners\ExpireApprovalProcessListener;
use Domain\Position\Listeners\RejectApprovalProcessListener;
use Domain\Position\Listeners\SendApprovedNotificationsListener;
use Domain\Position\Listeners\SendCanceledNotificationsListener;
use Domain\Position\Listeners\SendExpiredNotificationsListener;
use Domain\Position\Listeners\SendOpenedNotificationsListener;
use Domain\Position\Listeners\SendRejectedNotificationsListener;
use Domain\Position\Listeners\SendToApprovalListener;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Observers\ModelHasPositionObserver;
use Domain\Position\Observers\PositionApprovalObserver;
use Domain\Position\Observers\PositionObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PositionApprovalApprovedEvent::class => [
            ContinueApprovalProcessListener::class,
        ],
        PositionApprovalRejectedEvent::class => [
            RejectApprovalProcessListener::class,
        ],
        PositionApprovalCanceledEvent::class => [
            CancelApprovalProcessListener::class,
            SendCanceledNotificationsListener::class,
        ],
        PositionApprovalExpiredEvent::class => [
            ExpireApprovalProcessListener::class,
            SendExpiredNotificationsListener::class,
        ],
        PositionApprovedEvent::class => [
            SendApprovedNotificationsListener::class,
        ],
        PositionRejectedEvent::class => [
            SendRejectedNotificationsListener::class,
        ],
        PositionOpenedEvent::class => [
            SendOpenedNotificationsListener::class,
        ],
        PositionApprovalEvent::class => [
            SendToApprovalListener::class,
        ]
    ];

    protected $observers = [
        Position::class => PositionObserver::class,
        PositionApproval::class => PositionApprovalObserver::class,
        ModelHasPosition::class => ModelHasPositionObserver::class,
    ];
}
