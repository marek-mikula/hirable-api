<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Support\ActivityLog\Facades\CauserResolver;
use Symfony\Component\HttpFoundation\Response;

final class SetupActivityLogCauser
{
    public function handle(Request $request, \Closure $next): Response
    {
        if (Str::startsWith($request->path(), 'api')) {
            CauserResolver::setDefaultCauserResolver(static fn () => auth('api')->user());
        }

        return $next($request);
    }
}
