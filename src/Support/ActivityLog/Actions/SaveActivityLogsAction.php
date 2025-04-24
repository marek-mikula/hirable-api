<?php

namespace Support\ActivityLog\Actions;

use Lorisleiva\Actions\Action;
use Support\ActivityLog\Services\ActivityLogManager;
use Support\ActivityLog\Services\ActivityLogSaver;

class SaveActivityLogsAction extends Action
{
    public function __construct(
        private readonly ActivityLogManager $manager,
        private readonly ActivityLogSaver $saver,
    ) {
    }

    public function handle(): void
    {
        $this->saver->save($this->manager->pullLogs());
    }
}
