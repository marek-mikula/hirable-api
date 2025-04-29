<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

function actingAs(UserContract $user, $guard = null): void
{
    if (isset($user->wasRecentlyCreated) && $user->wasRecentlyCreated) {
        $user->wasRecentlyCreated = false;
    }

    auth()->guard($guard)->login($user);

    // tell auth, that default guard is the given one
    if ($guard) {
        auth()->shouldUse($guard);
    }
}

function actingAsGuest(?string $guard = null): void
{
    auth()->guard($guard)->logout();

    // forget Sanctum user because in tests
    // the state of the Sanctum guard stayed
    // the same and guest routes were acting
    // as if the user was still logged in
    auth()->guard('sanctum')->forgetUser();

    // invalidate the session because
    // of AuthenticateSession middleware
    if (auth()->guard($guard) instanceof SessionGuard) {
        session()->invalidate();
        session()->regenerateToken();
    }
}
