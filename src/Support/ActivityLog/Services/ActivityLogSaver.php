<?php

namespace Support\ActivityLog\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Support\ActivityLog\Data\Log;
use Support\ActivityLog\Jobs\SaveActivityLogsJob;

class ActivityLogSaver
{
    public function __construct(private readonly ActivityLogManager $manager)
    {
    }

    public function save(array $logs, bool $forceSync = false): void
    {
        if (empty($logs)) {
            return;
        }

        if ($forceSync || count($logs) <= $this->manager->getAsyncThreshold()) {
            $this->saveSync($logs);
        } else {
            $this->saveQueued($logs);
        }
    }

    private function saveQueued(array $logs): void
    {
        SaveActivityLogsJob::dispatch($logs);
    }

    private function saveSync(array $logs): void
    {
        $now = now()->format('Y-m-d H:i:s');

        DB::transaction(function () use ($logs, $now): void {
            collect($logs)
                ->map(static fn (Log $log): array => [
                    'causer_type' => $log->causer,
                    'causer_id' => $log->causerId,
                    'subject_type' => $log->subject,
                    'subject_id' => $log->subjectId,
                    'action' => $log->action,
                    'data' => empty($log->data) ? '{}' : json_encode($log->data),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->chunk(50)
                ->each(static function (Collection $chunk): void {
                    ActivityLog::query()->insert($chunk->all());
                });
        }, attempts: 5);
    }
}
