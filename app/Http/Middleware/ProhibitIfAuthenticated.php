<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use Domain\Auth\Http\Resources\AuthUserResource;
use Domain\User\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProhibitIfAuthenticated
{
    public function handle(Request $request, \Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (auth($guard)->check()) {
                if ($request->expectsJson()) {
                    $this->handleJsonResponse($guard);
                }

                return redirect('/');
            }
        }

        return $next($request);
    }

    /**
     * @throws HttpException
     */
    private function handleJsonResponse(?string $guard): never
    {
        /** @var User $user */
        $user = auth()->guard($guard)->user();

        // load needed relationships
        $user->loadMissing('company');

        throw new HttpException(responseCode: ResponseCodeEnum::GUEST_ONLY, data: [
            'user' => new AuthUserResource($user),
        ]);
    }
}
