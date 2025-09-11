<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Repositories\ModelHasPositionRepository;
use Domain\Position\Repositories\ModelHasPositionRepositoryInterface;
use Domain\Position\Repositories\PositionApprovalRepository;
use Domain\Position\Repositories\PositionApprovalRepositoryInterface;
use Domain\Position\Repositories\PositionCandidateActionRepository;
use Domain\Position\Repositories\PositionCandidateActionRepositoryInterface;
use Domain\Position\Repositories\PositionCandidateEvaluationRepository;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Domain\Position\Repositories\PositionCandidateRepository;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Domain\Position\Repositories\PositionCandidateShareRepository;
use Domain\Position\Repositories\PositionCandidateShareRepositoryInterface;
use Domain\Position\Repositories\PositionProcessStepRepository;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\Position\Repositories\PositionRepository;
use Domain\Position\Repositories\PositionRepositoryInterface;
use Domain\Position\Repositories\PositionSuggestRepository;
use Domain\Position\Repositories\PositionSuggestRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(PositionSuggestRepositoryInterface::class, PositionSuggestRepository::class);
        $this->app->bind(ModelHasPositionRepositoryInterface::class, ModelHasPositionRepository::class);
        $this->app->bind(PositionApprovalRepositoryInterface::class, PositionApprovalRepository::class);
        $this->app->bind(PositionCandidateRepositoryInterface::class, PositionCandidateRepository::class);
        $this->app->bind(PositionProcessStepRepositoryInterface::class, PositionProcessStepRepository::class);
        $this->app->bind(PositionCandidateActionRepositoryInterface::class, PositionCandidateActionRepository::class);
        $this->app->bind(PositionCandidateEvaluationRepositoryInterface::class, PositionCandidateEvaluationRepository::class);
        $this->app->bind(PositionCandidateShareRepositoryInterface::class, PositionCandidateShareRepository::class);
    }

    public function provides(): array
    {
        return [
            PositionRepositoryInterface::class,
            PositionSuggestRepositoryInterface::class,
            ModelHasPositionRepositoryInterface::class,
            PositionApprovalRepositoryInterface::class,
            PositionCandidateRepositoryInterface::class,
            PositionProcessStepRepositoryInterface::class,
            PositionCandidateActionRepositoryInterface::class,
            PositionCandidateEvaluationRepositoryInterface::class,
            PositionCandidateShareRepositoryInterface::class,
        ];
    }
}
