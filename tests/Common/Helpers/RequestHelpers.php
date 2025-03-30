<?php

namespace Tests\Common\Helpers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\Common\Data\ValidationData;

use function PHPUnit\Framework\assertSame;

function assertRequestValid(string $class, ValidationData $data): void
{
    // call before hook if any
    if ($data->before) {
        call_user_func($data->before);
    }

    $requestData = $data->data instanceof \Closure ? call_user_func($data->data) : $data->data;

    $keys = array_keys($requestData);

    // create request instance with given data
    $request = createRequest($class, data: $requestData, method: 'POST');

    // obtain rules from request class and filter only those
    // rules that we want to test with given validation data
    $rules = Arr::where($request->rules(), function (mixed $value, string $key) use ($keys): bool {
        return in_array(Str::before($key, '.'), $keys);
    });

    // create validator instance
    $validator = Validator::make(data: $requestData, rules: $rules);

    // assert invalid inputs
    assertSame($validator->getMessageBag()->keys(), $data->invalidInputs);

    // call after hook if any
    if ($data->after) {
        call_user_func($data->after);
    }
}

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
