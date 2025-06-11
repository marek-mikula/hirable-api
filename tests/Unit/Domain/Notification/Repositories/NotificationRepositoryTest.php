<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Notification\Repositories;

use Domain\Notification\Models\Notification;
use Domain\Notification\Repositories\NotificationRepositoryInterface;
use Domain\User\Models\User;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Notification\Repositories\NotificationRepository::markRead */
it('tests markRead method', function (): void {
    /** @var NotificationRepositoryInterface $repository */
    $repository = app(NotificationRepositoryInterface::class);

    $notification = Notification::factory()->unread()->create();

    assertFalse($notification->is_read);

    $notification = $repository->markRead($notification);

    assertTrue($notification->is_read);
});

/** @covers \Domain\Notification\Repositories\NotificationRepository::getUnreadForModel */
it('tests getUnreadForModel method', function (): void {
    /** @var NotificationRepositoryInterface $repository */
    $repository = app(NotificationRepositoryInterface::class);

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $notification1 = Notification::factory()->unread()->ofNotifiable($user1)->create();
    Notification::factory()->ofNotifiable($user1)->read(now())->create();
    $notification3 = Notification::factory()->unread()->ofNotifiable($user2)->create();
    Notification::factory()->ofNotifiable($user2)->read(now())->create();

    assertCollectionsAreSame(collect([$notification1]), $repository->getUnreadForModel($user1));
    assertCollectionsAreSame(collect([$notification3]), $repository->getUnreadForModel($user2));
});
