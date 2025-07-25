<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Auth\Database\Seeders\AuthDatabaseSeeder;
use Domain\Position\Database\Seeders\PositionProcessStepSeeder;
use Illuminate\Database\Seeder;
use Support\ActivityLog\Facades\ActivityLog;
use Support\Classifier\Database\Seeders\ClassifierDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        ActivityLog::withoutActivityLogs(function (): void {
            $this->call(AuthDatabaseSeeder::class);
            $this->call(ClassifierDatabaseSeeder::class);
            $this->call(PositionProcessStepSeeder::class);
        });
    }
}
