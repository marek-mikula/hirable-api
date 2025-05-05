<?php

declare(strict_types=1);

namespace Tests\Feature\Support\ActivityLog;

use Domain\User\Models\User;
use Support\ActivityLog\Facades\ActivityLog;
use Support\ActivityLog\Facades\CauserResolver;
use Tests\Common\Models\TestModel;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertSame;

uses()
    ->beforeEach(function (): void {
        ActivityLog::enable();
    })
    ->group('support', 'support-activity-log');

/**
 * @covers \Support\ActivityLog\Services\ActivityLogManager::enable
 * @covers \Support\ActivityLog\Services\ActivityLogManager::disable
 * @covers \Support\ActivityLog\Services\ActivityLogManager::isEnabled
 */
it('toggles logging', function (): void {
    assertCount(0, ActivityLog::getLogs());

    ActivityLog::disable();

    $model = new TestModel(['value' => 'Marek']);
    $model->save();

    assertCount(0, ActivityLog::getLogs());

    ActivityLog::enable();

    $model->value = 'Thomas';
    $model->save();

    assertCount(1, ActivityLog::getLogs());
});

/** @covers \Support\ActivityLog\Services\ActivityLogManager::withoutActivityLogs */
it('creates no logs when logging is disabled', function (): void {
    assertCount(0, ActivityLog::getLogs());

    ActivityLog::withoutActivityLogs(function (): void {
        // create
        $model = new TestModel(['value' => 'Marek']);
        $model->save();

        // update
        $model->value = 'Thomas';
        $model->save();

        // soft delete
        $model->delete();

        // restore
        $model->restore();

        // force delete
        $model->forceDelete();
    });

    // assert no logs were inserted
    assertCount(0, ActivityLog::getLogs());
});

/** @covers \Support\ActivityLog\Services\ActivityLogHandler::handleCreated */
it('creates log on created event', function (): void {
    /** @var User $causer */
    $causer = ActivityLog::withoutActivityLogs(fn (): User => User::factory()->create());

    CauserResolver::setCauser($causer);

    assertCount(0, ActivityLog::getLogs());

    $model = new TestModel(['value' => 'Marek']);
    $model->save();

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
    assertSame(TestModel::class, $log->subject);
    assertSame($model->id, $log->subjectId);
    assertSame('created', $log->action);
    assertSame(['value' => 'Marek'], $log->data);
});

/** @covers \Support\ActivityLog\Services\ActivityLogHandler::handleUpdated */
it('creates log on updated event', function (): void {
    /** @var TestModel $model */
    $model = ActivityLog::withoutActivityLogs(function (): TestModel {
        $model = new TestModel(['value' => 'Marek']);
        $model->save();

        return $model;
    });

    /** @var User $causer */
    $causer = ActivityLog::withoutActivityLogs(fn (): User => User::factory()->create());

    CauserResolver::setCauser($causer);

    assertCount(0, ActivityLog::getLogs());

    $model->value = 'Thomas';
    $model->save();

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
    assertSame(TestModel::class, $log->subject);
    assertSame($model->id, $log->subjectId);
    assertSame('updated', $log->action);
    assertSame([
        'value' => [
            'old' => 'Marek',
            'new' => 'Thomas',
        ],
    ], $log->data);
});

/** @covers \Support\ActivityLog\Services\ActivityLogHandler::handleDeleted */
it('creates log on deleted event', function (): void {
    /** @var TestModel $model */
    $model = ActivityLog::withoutActivityLogs(function (): TestModel {
        $model = new TestModel(['value' => 'Marek']);
        $model->save();

        return $model;
    });

    /** @var User $causer */
    $causer = ActivityLog::withoutActivityLogs(fn (): User => User::factory()->create());

    CauserResolver::setCauser($causer);

    assertCount(0, ActivityLog::getLogs());

    $model->delete(); // first try soft delete the model

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
    assertSame(TestModel::class, $log->subject);
    assertSame($model->id, $log->subjectId);
    assertSame('deleted', $log->action);
    assertSame(['soft' => 1], $log->data);

    ActivityLog::dumpLogs();

    assertCount(0, ActivityLog::getLogs());

    $model->forceDelete(); // now try force delete

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
    assertSame(TestModel::class, $log->subject);
    assertSame($model->id, $log->subjectId);
    assertSame('deleted', $log->action);
    assertSame(['soft' => 0], $log->data);
});

/** @covers \Support\ActivityLog\Services\ActivityLogHandler::handleRestored */
it('creates log on restored event', function (): void {
    /** @var TestModel $model */
    $model = ActivityLog::withoutActivityLogs(function (): TestModel {
        $model = new TestModel(['value' => 'Marek']);

        $model->save();
        $model->delete(); // soft-delete the model

        return $model;
    });

    /** @var User $causer */
    $causer = ActivityLog::withoutActivityLogs(fn (): User => User::factory()->create());

    CauserResolver::setCauser($causer);

    assertCount(0, ActivityLog::getLogs());

    $model->restore();

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
    assertSame(TestModel::class, $log->subject);
    assertSame($model->id, $log->subjectId);
    assertSame('restored', $log->action);
    assertSame([], $log->data);
});

/** @covers \Support\ActivityLog\Services\ActivityLogCauserResolver::withCauser */
it('sets specific causer for specific action', function (): void {
    $defaultCauser = User::factory()->create();
    $causer = User::factory()->create();

    CauserResolver::setDefaultCauserResolver(static fn () => $defaultCauser);

    assertCount(0, ActivityLog::getLogs());

    CauserResolver::withCauser(function (): void {
        $model = new TestModel(['value' => 'Marek']);
        $model->save();
    }, $causer);

    assertCount(1, ActivityLog::getLogs());

    $log = ActivityLog::getLogs()[0];

    assertSame(User::class, $log->causer);
    assertSame($causer->id, $log->causerId);
});
