<?php

declare(strict_types=1);

namespace Domain\User\Repositories;

use App\Enums\LanguageEnum;
use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\Input\UserUpdateInput;

final class UserRepository implements UserRepositoryInterface
{
    public function store(UserStoreInput $input): User
    {
        $user = new User();

        $user->company_id = $input->company->id;
        $user->company_role = $input->companyRole;
        $user->language = $input->language ?? LanguageEnum::EN;
        $user->firstname = $input->firstname;
        $user->lastname = $input->lastname;
        $user->prefix = $input->prefix;
        $user->postfix = $input->postfix;
        $user->phone = $input->phone;
        $user->email = $input->email;
        $user->password = $input->password;
        $user->agreement_ip = $input->agreementIp;
        $user->agreement_accepted_at = $input->agreementAcceptedAt;
        $user->email_verified_at = $input->emailVerifiedAt;

        throw_if(!$user->save(), RepositoryException::stored(User::class));

        $user->setRelation('company', $input->company);

        return $user;
    }

    public function update(User $user, UserUpdateInput $input): User
    {
        $user->firstname = $input->firstname;
        $user->lastname = $input->lastname;
        $user->email = $input->email;
        $user->timezone = $input->timezone;
        $user->notification_technical_mail = $input->notificationTechnicalMail;
        $user->notification_technical_app = $input->notificationTechnicalApp;
        $user->notification_marketing_mail = $input->notificationMarketingMail;
        $user->notification_marketing_app = $input->notificationMarketingApp;
        $user->notification_application_mail = $input->notificationApplicationMail;
        $user->notification_application_app = $input->notificationApplicationApp;
        $user->language = $input->language;
        $user->prefix = $input->prefix;
        $user->postfix = $input->postfix;
        $user->phone = $input->phone;

        throw_if(!$user->save(), RepositoryException::updated(User::class));

        return $user;
    }

    public function verifyEmail(User $user, ?Carbon $timestamp = null): User
    {
        $user->email_verified_at = $timestamp ?? now();

        throw_if(!$user->save(), RepositoryException::updated(User::class));

        return $user;
    }

    public function changePassword(User $user, string $password): User
    {
        $user->password = $password;

        throw_if(!$user->save(), RepositoryException::updated(User::class));

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', '=', $email)
            ->first();

        return $user;
    }
}
