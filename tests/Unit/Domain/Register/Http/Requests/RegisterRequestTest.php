<?php

namespace Tests\Unit\Domain\Register\Http\Requests;

use App\Models\Company;
use Domain\Register\Http\Requests\RegisterRegisterRequest;
use Illuminate\Support\Str;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Register\Http\Requests\RegisterRegisterRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    assertRequestValid(class: RegisterRegisterRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['firstname' => null], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['firstname' => 1], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['firstname' => Str::repeat('a', 256)], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['lastname' => null], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['lastname' => 1], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['lastname' => Str::repeat('a', 256)], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['password' => null], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 1], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password123'], invalidInputs: ['password'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => null], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => 1], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['password' => 'Password.123', 'passwordConfirm' => 'Password123'], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['companyName' => null], invalidInputs: ['companyName'])],
    [new ValidationData(data: ['companyName' => 1], invalidInputs: ['companyName'])],
    [new ValidationData(data: ['companyName' => Str::repeat('a', 256)], invalidInputs: ['companyName'])],
    [new ValidationData(data: ['companyEmail' => null], invalidInputs: ['companyEmail'])],
    [new ValidationData(data: ['companyEmail' => 1], invalidInputs: ['companyEmail'])],
    [new ValidationData(data: ['companyEmail' => 'test'], invalidInputs: ['companyEmail'])],
    [new ValidationData(data: ['companyEmail' => 'test'.Str::repeat('a', 256).'@example.com'], invalidInputs: ['companyEmail'])],
    [new ValidationData(data: ['companyEmail' => 'test@example.com'], invalidInputs: ['companyEmail'], before: function (): void {
        Company::factory()->ofEmail('test@example.com')->create();
    })],
    [new ValidationData(data: ['companyIdNumber' => null], invalidInputs: ['companyIdNumber'])],
    [new ValidationData(data: ['companyIdNumber' => 1], invalidInputs: ['companyIdNumber'])],
    [new ValidationData(data: ['companyIdNumber' => Str::repeat('a', 256)], invalidInputs: ['companyIdNumber'])],
    [new ValidationData(data: ['companyIdNumber' => '87395374'], invalidInputs: ['companyIdNumber'], before: function (): void {
        Company::factory()->ofIdNumber('87395374')->create();
    })],
    [new ValidationData(data: ['companyWebsite' => 1], invalidInputs: ['companyWebsite'])],
    [new ValidationData(data: ['companyWebsite' => 'test'], invalidInputs: ['companyWebsite'])],
    [new ValidationData(data: ['companyWebsite' => 'https://www.'.Str::repeat('a', 256).'.com'], invalidInputs: ['companyWebsite'])],
    [new ValidationData(data: [
        'firstname' => 'Thomas',
        'lastname' => 'Cook',
        'password' => 'Password.123',
        'passwordConfirm' => 'Password.123',
        'companyName' => 'J&T Company',
        'companyEmail' => 'jt@example.com',
        'companyIdNumber' => '85838395',
        'companyWebsite' => 'https://www.example.com',
    ], invalidInputs: [])],
]);
