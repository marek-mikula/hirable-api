<?php

declare(strict_types=1);

namespace Tests\Unit\Support\ActivityLog\Services;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Queue;
use Support\ActivityLog\Data\Log;
use Support\ActivityLog\Jobs\SaveActivityLogsJob;
use Support\ActivityLog\Models\ActivityLog;
use Support\ActivityLog\Services\ActivityLogSaver;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;

function generateLogs(int $n): array
{
    $logs = [];

    for ($i = 0; $i < $n; $i++) {
        $logs[] = generateLog();
    }

    return $logs;
}

function generateLog(): Log
{
    $models = [
        User::class,
    ];

    $actions = [
        'created',
        'updated',
        'deleted',
    ];

    return Log::from([
        'causer' => fake()->randomElement($models),
        'causerId' => fake()->randomNumber(),
        'subject' => fake()->randomElement($models),
        'subjectId' => fake()->randomNumber(),
        'action' => fake()->randomElement($actions),
        'data' => [],
    ]);
}

/**
 * @covers \Support\ActivityLog\Services\ActivityLogSaver::save
 * @covers \Support\ActivityLog\Services\ActivityLogSaver::saveSync
 * @covers \Support\ActivityLog\Services\ActivityLogSaver::saveQueued
 */
it('correctly saves logs', function (): void {
    Queue::fake([
        SaveActivityLogsJob::class,
    ]);

    /** @var ActivityLogSaver $saver */
    $saver = app(ActivityLogSaver::class);

    assertDatabaseEmpty(ActivityLog::class);

    // test immediate save of logs
    $saver->save(generateLogs(n: 5));

    assertDatabaseCount(ActivityLog::class, 5);

    $saver->save(generateLogs(n: 20));

    // assert that DB still has 5 entries, because
    // the next batch should be saved via queue
    assertDatabaseCount(ActivityLog::class, 5);

    Queue::assertPushed(SaveActivityLogsJob::class);
});
