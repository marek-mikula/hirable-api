<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Register\Http\Requests;

use Domain\Register\Http\Requests\RegisterRequestRequest;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Register\Http\Requests\RegisterRequestRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    assertRequestValid(class: RegisterRequestRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['email' => null], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 1], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 'test'], invalidInputs: ['email'])],
    [new ValidationData(data: ['email' => 'test@example.com'], invalidInputs: [])],
]);
