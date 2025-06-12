<?php

declare(strict_types=1);

namespace Tests\Process;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Company\Notifications\InvitationAcceptedNotification;
use Domain\Company\Notifications\InvitationSentNotification;
use Domain\Register\Notifications\RegisterRegisteredNotification;
use Domain\User\Models\User;
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
use function Tests\Common\Helpers\actingAs;
use function Tests\Common\Helpers\actingAsGuest;
use function Tests\Common\Helpers\assertResponse;

it('tests invitation process', function (): void {
    // process contains these steps:
    // 1. creation of the invitation
    // 2. registration
    // 3. call of /auth/me endpoint
    // 4. logout
    // 5. login

    $company = Company::factory()->create();
    $companyUser = User::factory()->ofCompany($company, RoleEnum::ADMIN)->create();

    assertDatabaseEmpty(Token::class);

    $role = RoleEnum::RECRUITER;
    $email = fake()->safeEmail;

    actingAs($companyUser, 'api');

    $response = postJson(route('api.companies.invitations.store', ['company' => $company->id]), [
        'role' => $role->value,
        'email' => $email,
    ]);

    assertResponse($response, ResponseCodeEnum::SUCCESS);

    assertDatabaseHas(Token::class, [
        'type' => TokenTypeEnum::INVITATION->value,
        'user_id' => $companyUser->id,
        'data->companyId' => $company->id,
        'data->role' => $role->value,
        'data->email' => $email,
    ]);

    Notification::assertSentOnDemand(InvitationSentNotification::class);

    /** @var Token $token */
    $token = Token::query()
        ->where('type', '=', TokenTypeEnum::INVITATION->value)
        ->where('user_id', '=', $companyUser->id)
        ->first();

    actingAsGuest('api');

    $data = [
        'firstname' => fake()->firstName,
        'lastname' => fake()->lastName,
        'password' => 'Test.123',
        'passwordConfirm' => 'Test.123',
    ];

    $response = postJson(route('api.register.register', [
        'token' => $token->secret_token,
    ]), $data, [
        // send additional language header
        // to test, that new user is created
        // with this language
        'Accept-Language' => LanguageEnum::CS->value,
    ]);

    $token->refresh();

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertAuthenticated('api');

    assertDatabaseHas(User::class, [
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'language' => LanguageEnum::CS->value,
        'email' => $email,
        'company_id' => $company->id,
        'company_role' => $role->value,
        'company_owner' => false,
    ]);

    // token for invitation should be marked as used
    assertNotNull($token->used_at);

    /** @var User $user */
    $user = User::query()->whereEmail($email)->first();

    // assert registered notification has been sent
    Notification::assertSentTo($user, RegisterRegisteredNotification::class);

    // assert the creator of invitation has been notified
    Notification::assertSentTo($companyUser, InvitationAcceptedNotification::class);

    // try to get user resource from secured endpoint
    $response = getJson(route('api.auth.me'));

    assertResponse($response, ResponseCodeEnum::SUCCESS);

    // try to log out
    $response = postJson(route('api.auth.logout'));

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertGuest('api');

    // try to log in again
    $response = postJson(route('api.auth.login'), ['email' => $email, 'password' => $data['password']]);

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertAuthenticated('api');
});
