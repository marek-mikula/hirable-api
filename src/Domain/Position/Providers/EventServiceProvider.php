<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Listeners\CancelApprovalProcessListener;
use Domain\Position\Listeners\ContinueApprovalProcessListener;
use Domain\Position\Listeners\ExpireApprovalProcessListener;
use Domain\Position\Listeners\RejectApprovalProcessListener;
use Domain\Position\Listeners\SendApprovedNotificationsListener;
use Domain\Position\Listeners\SendCanceledNotificationsListener;
use Domain\Position\Listeners\SendExpiredNotificationsListener;
use Domain\Position\Listeners\SendRejectedNotificationsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PositionApprovalApprovedEvent::class => [
            ContinueApprovalProcessListener::class,
            SendApprovedNotificationsListener::class,
        ],
        PositionApprovalRejectedEvent::class => [
            RejectApprovalProcessListener::class,
            SendRejectedNotificationsListener::class,
        ],
        PositionApprovalCanceledEvent::class => [
            CancelApprovalProcessListener::class,
            SendCanceledNotificationsListener::class,
        ],
        PositionApprovalExpiredEvent::class => [
            ExpireApprovalProcessListener::class,
            SendExpiredNotificationsListener::class,
        ]
    ];
}
