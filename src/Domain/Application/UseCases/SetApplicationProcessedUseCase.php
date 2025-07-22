<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\UseCases\UseCase;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SetApplicationProcessedUseCase extends UseCase
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
    ) {
    }

    public function handle(Application $application): Application
    {
        return DB::transaction(function () use ($application): Application {
            return $this->applicationRepository->setProcessed($application);
        }, attempts: 5);
    }
}
