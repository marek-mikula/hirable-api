<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Domain\User\Models\User;

class AuthRequest extends Request
{
    public function user($guard = null): User // @pest-ignore-type
    {
        return once(function () use ($guard): User {
            /** @var User|null $user */
            $user = parent::user($guard);

            throw_if(empty($user), new \Exception(sprintf('Unauthenticated user in %s. Add auth middleware.', AuthRequest::class)));

            return $user;
        });
    }
}
