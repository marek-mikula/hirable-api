<?php

use App\Enums\LanguageEnum;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Http\FormRequest;

use function PHPUnit\Framework\assertSame;
use function Tests\Common\Helpers\createRequest;

/** @covers \App\Http\Middleware\SetLocale::handle */
it('correctly sets application language', function (): void {
    app()->setLocale(LanguageEnum::EN->value);

    $request = createRequest(FormRequest::class, headers: [
        'Accept-Language' => LanguageEnum::CS->value,
    ]);

    /** @var SetLocale $middleware */
    $middleware = app(SetLocale::class);

    $middleware->handle($request, static fn () => response()->json());

    assertSame(LanguageEnum::CS->value, app()->getLocale());
});

/** @covers \App\Http\Middleware\SetLocale::handle */
it('correctly sets default application language when invalid header is passed', function (): void {
    // set default locale to config
    config()->set('app.locale', LanguageEnum::CS->value);

    $request = createRequest(FormRequest::class, headers: [
        'Accept-Language' => 'something invalid',
    ]);

    /** @var SetLocale $middleware */
    $middleware = app(SetLocale::class);

    $middleware->handle($request, static fn () => response()->json());

    assertSame(LanguageEnum::CS->value, app()->getLocale());
});
