<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Support\File\Models\File;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootModelBinding();
    }

    private function bootModelBinding(): void
    {
        Route::bind('file', static function (string $value): File {
            /** @var User|null $user */
            $user = request()->user();

            throw_if(!$user, new \Exception('User is not logged in. Cannot scope file.'));

            /** @var Position|null $position */
            $position = request()->route('position');

            if ($position) {
                /** @var File $file */
                $file = $position->files()->findOrFail((int) $value);
            } else {
                throw new \Exception('File needs to be scoped by another model.');
            }

            return $file;
        });
    }
}
