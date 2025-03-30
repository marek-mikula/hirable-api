<?php

namespace Support\NotificationPreview\Console\Commands;

use App\Enums\NotificationTypeEnum;
use Illuminate\Console\Command;
use Support\NotificationPreview\Data\NotificationData;
use Support\NotificationPreview\Data\NotificationDomain;
use Support\NotificationPreview\Services\NotificationRegistrar;

class CheckCommand extends Command
{
    protected $signature = 'notification-preview:check';

    protected $description = 'Checks if all types of notifications are listed in the preview.';

    public function handle(NotificationRegistrar $notificationRegistrar): int
    {
        $types = $notificationRegistrar
            ->getNotifications()
            ->map(static fn (NotificationDomain $domain) => $domain->notifications)
            ->flatten()
            ->map(static fn (NotificationData $notification) => $notification->getType()->value);

        $allTypes = collect(NotificationTypeEnum::cases())->pluck('value');

        $missingTypes = $allTypes->diff($types);

        if ($missingTypes->isEmpty()) {
            $this->info('No missing notification types in preview found.');

            return 0;
        }

        $this->error(vsprintf('There are some missing notification types in the preview: %s.', [
            $missingTypes->implode(', '),
        ]));

        return 1;
    }
}
