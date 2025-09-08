<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Events\PositionApprovalApprovedEvent;
use Domain\Position\Events\PositionApprovalCanceledEvent;
use Domain\Position\Events\PositionApprovalEvent;
use Domain\Position\Events\PositionApprovalExpiredEvent;
use Domain\Position\Events\PositionApprovalRejectedEvent;
use Domain\Position\Events\PositionApprovedEvent;
use Domain\Position\Events\PositionCandidateCreatedEvent;
use Domain\Position\Events\PositionCandidateShareCreatedEvent;
use Domain\Position\Events\PositionCandidateShareDeletedEvent;
use Domain\Position\Events\PositionOpenedEvent;
use Domain\Position\Events\PositionRejectedEvent;
use Domain\Position\Listeners\ApprovalCancelProcessListener;
use Domain\Position\Listeners\ApprovalContinueProcessListener;
use Domain\Position\Listeners\CreateProcessStepsListener;
use Domain\Position\Listeners\ApprovalExpireProcessListener;
use Domain\Position\Listeners\ApprovalRejectProcessListener;
use Domain\Position\Listeners\SendPositionApprovedNotificationsListener;
use Domain\Position\Listeners\SendApprovalCanceledNotificationsListener;
use Domain\Position\Listeners\SendApprovalExpiredNotificationsListener;
use Domain\Position\Listeners\SendNewCandidateNotificationListener;
use Domain\Position\Listeners\SendPositionCandidateSharedNotificationListener;
use Domain\Position\Listeners\SendPositionCandidateShareStoppedNotificationListener;
use Domain\Position\Listeners\SendPositionOpenedNotificationsListener;
use Domain\Position\Listeners\SendApprovalRejectedNotificationsListener;
use Domain\Position\Listeners\SendToApprovalListener;
use Domain\Position\Listeners\SetTokensListener;
use Domain\Position\Listeners\EvaluatePositionCandidateListener;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Observers\ModelHasPositionObserver;
use Domain\Position\Observers\PositionApprovalObserver;
use Domain\Position\Observers\PositionCandidateObserver;
use Domain\Position\Observers\PositionCandidateShareObserver;
use Domain\Position\Observers\PositionObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PositionApprovalApprovedEvent::class => [
            ApprovalContinueProcessListener::class,
        ],
        PositionApprovalRejectedEvent::class => [
            ApprovalRejectProcessListener::class,
        ],
        PositionApprovalCanceledEvent::class => [
            ApprovalCancelProcessListener::class,
            SendApprovalCanceledNotificationsListener::class,
        ],
        PositionApprovalExpiredEvent::class => [
            ApprovalExpireProcessListener::class,
            SendApprovalExpiredNotificationsListener::class,
        ],
        PositionApprovedEvent::class => [
            SendPositionApprovedNotificationsListener::class,
        ],
        PositionRejectedEvent::class => [
            SendApprovalRejectedNotificationsListener::class,
        ],
        PositionOpenedEvent::class => [
            SetTokensListener::class,
            CreateProcessStepsListener::class,
            SendPositionOpenedNotificationsListener::class,
        ],
        PositionApprovalEvent::class => [
            SendToApprovalListener::class,
        ],
        PositionCandidateCreatedEvent::class => [
            EvaluatePositionCandidateListener::class,
            SendNewCandidateNotificationListener::class,
        ],
        PositionCandidateShareCreatedEvent::class => [
            SendPositionCandidateSharedNotificationListener::class,
        ],
        PositionCandidateShareDeletedEvent::class => [
            SendPositionCandidateShareStoppedNotificationListener::class,
        ],
    ];

    protected $observers = [
        Position::class => PositionObserver::class,
        PositionApproval::class => PositionApprovalObserver::class,
        ModelHasPosition::class => ModelHasPositionObserver::class,
        PositionCandidate::class => PositionCandidateObserver::class,
        PositionCandidateShare::class => PositionCandidateShareObserver::class
    ];
}
