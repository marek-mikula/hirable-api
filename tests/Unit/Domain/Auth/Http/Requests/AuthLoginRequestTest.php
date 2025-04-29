<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Auth\Http\Requests;

use Domain\Auth\Http\Requests\AuthLoginRequest;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Auth\Http\Requests\AuthLoginRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    assertRequestValid(class: AuthLoginRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['email' => null], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 1], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 'test'], invalidInputs: ['email'])],
    [new ValidationData(data: ['password' => null], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 1], invalidInputs: ['password'])],
    [new ValidationData(data: ['rememberMe' => 'test'], invalidInputs: ['rememberMe'])],
    [new ValidationData(data: [
        'email' => 'test@example.com',
        'password' => 'password',
        'rememberMe' => 0,
    ], invalidInputs: [])],
]);
