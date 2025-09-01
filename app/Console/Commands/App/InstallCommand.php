<?php

declare(strict_types=1);

namespace App\Console\Commands\App;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Support\File\Enums\FileDiskEnum;

class InstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Installs application.';

    public function handle(): int
    {
        // clear cache, config, routes, etc.
        $this->call('optimize:clear');

        // migrate fresh database tables
        $this->call('migrate:fresh');

        // seed default data
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);

        // clear all files in local storage
        $this->clearLocalStorage();

        // clear all laravel log files
        $this->clearLogs();

        // clear queue log
        $this->clearQueueLog();

        // link storage folder
        if (!$this->isStorageLinked()) {
            $this->call('storage:link');
        }

        $this->components->info('App installed.');

        return 0;
    }

    private function isStorageLinked(): bool
    {
        return file_exists(public_path('/storage'));
    }

    private function clearLocalStorage(): void
    {
        $this->components->info('Clearing local storage.');

        $storage = Storage::disk(FileDiskEnum::LOCAL->value);

        foreach ($storage->allFiles() as $file) {
            if (Str::contains($file, '.gitignore')) {
                continue;
            }

            $this->components->task(sprintf('Deleting file %s', $file), function () use (
                $storage,
                $file,
            ): void {
                $storage->delete($file);
            });
        }

        foreach (array_reverse($storage->allDirectories()) as $directory) {
            if ($directory === 'public') {
                continue;
            }

            $storage->deleteDirectory($directory);

            $this->components->task(sprintf('Deleting directory %s', $directory), function () use (
                $storage,
                $directory,
            ): void {
                $storage->deleteDirectory($directory);
            });
        }

        $this->newLine();
    }

    private function clearLogs(): void
    {
        $this->components->info('Clearing logs.');

        $logs = glob(storage_path('/logs/*.log'));

        foreach ($logs as $log) {
            $this->components->task(sprintf('Clearing log %s', basename($log)), function () use ($log): void {
                unlink($log);
            });
        }

        $this->newLine();
    }

    private function clearQueueLog(): void
    {
        $this->components->info('Clearing queue log.');

        $path = base_path('queue.log');

        if (!file_exists($path)) {
            $this->components->info('Queue log does not exist.');
            return;
        }

        $this->components->task('Clearing queue log.', function () use ($path): void {
            file_put_contents($path, '');
        });

        $this->newLine();
    }
}
