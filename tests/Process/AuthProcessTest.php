<?php

declare(strict_types=1);

namespace Tests\Process;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Register\Events\UserRegistered;
use Domain\Register\Notifications\RegisterRequestNotification;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertResponse;

it('tests auth process - registration, login, logout', function (): void {
    // process contains these steps:
    // 1. register request
    // 2. registration
    // 3. call of /auth/me endpoint
    // 4. logout
    // 5. login

    Event::fake([
        UserRegistered::class,
    ]);

    $email = 'example@example.com';
    $password = 'Test.123';

    $companyEmail = 'info@example.com';

    assertDatabaseEmpty(User::class);
    assertDatabaseEmpty(Token::class);

    // first initiate the register process
    $response = postJson(route('api.register.request'), [
        'email' => $email,
    ]);

    assertResponse($response, ResponseCodeEnum::SUCCESS);

    assertDatabaseHas(Token::class, [
        'type' => TokenTypeEnum::REGISTRATION->value,
        'data->email' => $email,
    ]);

    /** @var Token $token */
    $token = Token::query()
        ->where('type', '=', TokenTypeEnum::REGISTRATION->value)
        ->where('data->email', '=', $email)
        ->first();

    Notification::assertSentOnDemand(RegisterRequestNotification::class);

    assertGuest('api');

    // finish the registration process
    $response = postJson(route('api.register.register', [
        'token' => $token->secret_token,
    ]), [
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'password' => $password,
        'passwordConfirm' => $password,
        'companyName' => 'Hirable s.r.o.',
        'companyEmail' => $companyEmail,
        'companyIdNumber' => '999000111',
        'companyWebsite' => 'https://www.example.com',
    ], [
        // send additional language header
        // to test, that new user is created
        // with this language
        'Accept-Language' => LanguageEnum::CS->value,
    ]);

    $token->refresh();

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertDatabaseHas(User::class, ['email' => $email]);
    assertDatabaseHas(Company::class, ['email' => $companyEmail]);
    assertAuthenticated('api');

    /** @var User $user */
    $user = User::query()->whereEmail($email)->first();

    /** @var Company $company */
    $company = Company::query()->whereEmail($companyEmail)->first();

    // assert correct user attributes
    assertSame('Thomas', $user->firstname);
    assertSame('Example', $user->lastname);
    assertSame($email, $user->email);
    assertSame($company->id, $user->company_id);
    assertSame(RoleEnum::ADMIN, $user->company_role);
    assertTrue($user->company_owner);

    // assert correct user language
    // retrieved from Accept-Language
    assertSame(LanguageEnum::CS, $user->language);

    // assert that user's email address got
    // automatically verified
    assertNotNull($user->email_verified_at);
    assertTrue($user->is_email_verified);

    // assert correct company attributes
    assertSame('Hirable s.r.o.', $company->name);
    assertSame($companyEmail, $company->email);
    assertSame('999000111', $company->id_number);
    assertSame('https://www.example.com', $company->website);

    Event::assertDispatched(UserRegistered::class);

    // token for registration should be marked as used
    assertNotNull($token->used_at);

    // try to get user resource from secured endpoint
    $response = getJson(route('api.auth.me'));

    assertResponse($response, ResponseCodeEnum::SUCCESS);

    // try to log out
    $response = postJson(route('api.auth.logout'));

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertGuest('api');

    // try to log in again
    $response = postJson(route('api.auth.login'), ['email' => $email, 'password' => $password]);

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertAuthenticated('api');
});
