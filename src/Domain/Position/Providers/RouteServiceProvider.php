<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Domain\User\Models\User;
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
        Route::bind('position', static function (string $value): Position {
            /** @var User|null $user */
            $user = request()->user();

            throw_if(!$user, new \Exception('User is not logged in. Cannot scope position.'));

            $company = $user->loadMissing('company')->company;

            /** @var Position $position */
            $position = $company->positions()->findOrFail((int) $value);

            return $position;
        });

        Route::bind('approval', static function (string $value): PositionApproval {
            /** @var User|null $user */
            $user = request()->user();

            throw_if(!$user, new \Exception('User is not logged in. Cannot scope position approval.'));

            /** @var Position|null $position */
            $position = request()->route('position');

            throw_if(!$position, new \Exception('Position is not defined. Cannot scope position approval.'));

            /** @var PositionApproval $approval */
            $approval = $position->approvals()->findOrFail((int) $value);

            return $approval;
        });
    }
}
