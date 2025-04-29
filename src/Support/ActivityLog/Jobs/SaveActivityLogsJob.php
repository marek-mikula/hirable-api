<?php

declare(strict_types=1);

namespace Support\ActivityLog\Jobs;

use App\Jobs\CommonJob;
use Support\ActivityLog\Services\ActivityLogSaver;

class SaveActivityLogsJob extends CommonJob
{
    public function __construct(private readonly array $logs)
    {
        parent::__construct();
    }

    public function handle(ActivityLogSaver $saver): void
    {
        $saver->save($this->logs, forceSync: true);
    }
}
