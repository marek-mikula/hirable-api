<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Models\PositionProcessStep;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('api/positions')
                ->name('api.positions.')
                ->group(__DIR__.'/../Routes/position.php');
        });
    }

    private function bootModelBinding(): void
    {
        Route::model('position', Position::class);

        Route::bind('positionApproval', function (string $value): PositionApproval {
            /** @var Position|null $position */
            $position = request()->route('position');

            throw_if(empty($position), new \RuntimeException('Missing position model for route binding.'));

            return $position->approvals()->findOrFail($value);
        });

        Route::bind('positionProcessStep', function (string $value): PositionProcessStep {
            /** @var Position|null $position */
            $position = request()->route('position');

            throw_if(empty($position), new \RuntimeException('Missing position model for route binding.'));

            return $position->steps()->findOrFail($value);
        });

        Route::bind('positionCandidate', function (string $value): PositionCandidate {
            /** @var Position|null $position */
            $position = request()->route('position');

            throw_if(empty($position), new \RuntimeException('Missing position model for route binding.'));

            return $position->positionCandidates()->findOrFail($value);
        });

        Route::bind('positionCandidateAction', function (string $value): PositionCandidateAction {
            /** @var PositionCandidate|null $positionCandidate */
            $positionCandidate = request()->route('positionCandidate');

            throw_if(empty($positionCandidate), new \RuntimeException('Missing position candidate model for route binding.'));

            return $positionCandidate->actions()->findOrFail($value);
        });

        Route::bind('positionCandidateShare', function (string $value): PositionCandidateShare {
            /** @var PositionCandidate|null $positionCandidate */
            $positionCandidate = request()->route('positionCandidate');

            throw_if(empty($positionCandidate), new \RuntimeException('Missing position candidate model for route binding.'));

            return $positionCandidate->shares()->findOrFail($value);
        });

        Route::bind('positionCandidateEvaluation', function (string $value): PositionCandidateEvaluation {
            /** @var PositionCandidate|null $positionCandidate */
            $positionCandidate = request()->route('positionCandidate');

            throw_if(empty($positionCandidate), new \RuntimeException('Missing position candidate model for route binding.'));

            return $positionCandidate->evaluations()->findOrFail($value);
        });
    }
}
