<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Policies\PositionApprovalPolicy;
use Domain\Position\Policies\PositionPolicy;
use Domain\Position\Policies\PositionProcessStepPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Position::class => PositionPolicy::class,
        PositionApproval::class => PositionApprovalPolicy::class,
        PositionProcessStep::class => PositionProcessStepPolicy::class,
    ];
}
