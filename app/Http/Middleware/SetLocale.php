<?php

namespace App\Http\Middleware;

use App\Enums\LanguageEnum;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, \Closure $next): Response
    {
        // Try to parse language from:
        // a) query string
        // b) Accept-Language header
        // c) default value
        $locale = $request->query('lang', $request->header('Accept-Language', (string) config('app.locale')));

        $locale = LanguageEnum::tryFrom($locale);

        if (! $locale) {
            $locale = LanguageEnum::from((string) config('app.locale'));
        }

        app()->setLocale($locale->value);

        return $next($request);
    }
}
