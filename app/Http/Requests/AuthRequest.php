<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;

class AuthRequest extends Request
{
    public function user($guard = null): User
    {
        return once(function () use ($guard): User {
            /** @var User|null $user */
            $user = parent::user($guard);

            throw_if(empty($user), new \Exception(vsprintf('Unauthenticated user in %s. Add auth middleware.', [
                AuthRequest::class,
            ])));

            return $user;
        });
    }
}
