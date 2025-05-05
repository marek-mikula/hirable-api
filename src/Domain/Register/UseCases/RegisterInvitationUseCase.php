<?php

declare(strict_types=1);

namespace Domain\Register\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Notifications\InvitationAcceptedNotification;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Register\Http\Requests\Data\RegisterData;
use Domain\Register\Notifications\RegisterRegisteredNotification;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

class RegisterInvitationUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(Token $token, RegisterData $data): User
    {
        throw_if(
            condition: !$token->hasDataValue('email', 'role', 'companyId') || !$token->user_id || $token->type !== TokenTypeEnum::INVITATION,
            exception: new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID)
        );

        $company = $this->companyRepository->find((int) $token->getDataValue('companyId'));

        throw_if(
            condition: !$company,
            exception: new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID)
        );

        $creator = $token->loadMissing('user')->user;
        $email = (string) $token->getDataValue('email');
        $role = RoleEnum::from((string) $token->getDataValue('role'));

        return DB::transaction(function () use (
            $token,
            $data,
            $creator,
            $email,
            $company,
            $role,
        ): User {
            $user = $this->userRepository->store(new UserStoreInput(
                language: LanguageEnum::tryFrom(app()->getLocale()),
                firstname: $data->firstname,
                lastname: $data->lastname,
                email: $email,
                password: $data->password,
                agreementIp: $data->agreementIp,
                agreementAcceptedAt: $data->agreementAcceptedAt,
                // mark immediately email as verified, because
                // user has registered through a link, which he
                // received to his email => we can safely say
                // the email is verified
                company: $company,
                companyRole: $role,
                emailVerifiedAt: now(),
            ));

            $this->tokenRepository->markUsed($token);

            $user->notify(new RegisterRegisteredNotification());

            $creator->notify(new InvitationAcceptedNotification($user));

            return $user;
        }, attempts: 5);
    }
}
