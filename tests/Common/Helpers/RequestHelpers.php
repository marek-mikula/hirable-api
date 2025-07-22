<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Creates Laravel request class
 *
 * @template TRequestClass of FormRequest
 *
 * @param  class-string<TRequestClass>  $class
 * @return TRequestClass
 */
function createRequest(
    string $class,
    array $data = [],
    string $uri = '/',
    string $method = 'GET',
    array $bindings = [],
    array $headers = [],
    bool $loadSession = false,
) {
    $symphonyRequest = Request::create(
        uri: $uri,
        method: $method,
        parameters: $data,
    );

    /** @var FormRequest $request */
    $request = $class::createFromBase($symphonyRequest);

    // create route object so we
    // can mock route binding
    $route = new Route(methods: [$method], uri: $uri, action: static fn () => null);

    // bind the route object to request object
    $route->bind($request);

    // set up needed route bindings
    foreach ($bindings as $parameter => $value) {
        $route->setParameter($parameter, $value);
    }

    // set up needed headers
    foreach ($headers as $header => $value) {
        $request->headers->set($header, $value);
    }

    // set route resolver to request class
    $request->setRouteResolver(static fn () => $route);

    // set correct user resolver
    $request->setUserResolver(static fn ($guard = null) => auth()->guard($guard)->user());

    if ($loadSession) {
        $session = session()->driver();
        $session->setRequestOnHandler($request);
        $request->setLaravelSession($session);
    }

    return $request;
}
