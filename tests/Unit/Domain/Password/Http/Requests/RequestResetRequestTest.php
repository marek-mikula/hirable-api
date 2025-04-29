<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Password\Http\Requests;

use Domain\Password\Http\Requests\PasswordRequestResetRequest;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Password\Http\Requests\PasswordRequestResetRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    assertRequestValid(class: PasswordRequestResetRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['email' => null], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 1], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 'test'], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 'test@example.com'], invalidInputs: [])],
]);
