<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Password\Http\Requests;

use Domain\Password\Http\Requests\PasswordResetRequest;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Password\Http\Requests\PasswordResetRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    assertRequestValid(class: PasswordResetRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['password' => null], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 1], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password123'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => null], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => 1], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => 'Password123'], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: [
        'password' => 'Password.123',
        'passwordConfirm' => 'Password.123',
    ], invalidInputs: [])],
]);
