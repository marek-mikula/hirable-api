<?php

namespace App\Console\Commands\App;

use Database\Seeders\DefaultDatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Support\File\Enums\FileDomainEnum;

class InstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Installs fresh application.';

    public function handle(): int
    {
        // clear cache, config, routes, etc.
        $this->call('optimize:clear');

        // migrate fresh database tables
        $this->call('migrate:fresh');

        // seed default data
        $this->call('db:seed', ['--class' => DefaultDatabaseSeeder::class]);

        // clear all files in storage
        $this->clearStorage();

        // link storage folder
        if (! $this->isStorageLinked()) {
            $this->call('storage:link');
        }

        return 0;
    }

    private function isStorageLinked(): bool
    {
        return file_exists(public_path('/storage'));
    }

    private function clearStorage(): void
    {
        $this->components->info('Clearing files on storage disks.');

        // clean all previous files in storage
        foreach (FileDomainEnum::cases() as $domain) {
            $this->components->task("Clearing {$domain->getDisk()} disk", function () use ($domain): void {
                $storage = Storage::disk($domain->getDisk());

                foreach ($storage->directories('/') as $directory) {
                    $storage->deleteDirectory($directory);
                }

                foreach ($storage->files('/') as $file) {
                    $storage->delete($file);
                }
            });
        }

        $this->newLine();
    }
}
