<?php

declare(strict_types=1);

namespace App\Console\Commands\Schedule;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

class RunCommand extends Command
{
    protected $signature = 'schedule:run';

    protected $description = 'Runs a specific scheduled task.';

    public function handle(Schedule $schedule): int
    {
        $events = collect($schedule->events())->filter(static fn (Event $event): bool => !empty($event->description));

        $selectedEvent = $this->choice('Which schedule you want to run?', $events->pluck('description')->all());

        /** @var Event $event */
        $event = $events->first(static fn (Event $event): bool => $event->description === $selectedEvent);

        if (!$this->confirm(sprintf('Are you sure you want to run the "%s" schedule?', $event->description), true)) {
            return 0;
        }

        try {
            $event->run(app());
        } catch (\Exception $e) {
            $this->error(sprintf('Schedule failed with message "%s".', $e->getMessage()));

            return 1;
        }

        $this->info('Schedule ran successfully.');

        return 0;
    }
}
