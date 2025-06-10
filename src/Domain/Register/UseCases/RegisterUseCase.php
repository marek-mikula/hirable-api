<?php

declare(strict_types=1);

namespace Domain\Register\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyStoreInput;
use Domain\Register\Events\UserRegistered;
use Domain\Register\Http\Requests\Data\RegisterData;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

class RegisterUseCase extends UseCase
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
            condition: !$token->hasDataValue('email') || $token->type !== TokenTypeEnum::REGISTRATION,
            exception: new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID)
        );

        throw_if(
            condition: !$data->company,
            exception: new \Exception('Basic registration needs company data.')
        );

        $email = (string) $token->getDataValue('email');

        return DB::transaction(function () use (
            $token,
            $data,
            $email,
        ): User {
            $company = $this->companyRepository->store(new CompanyStoreInput(
                name: $data->company->name,
                email: $data->company->email,
                idNumber: $data->company->idNumber,
                website: $data->company->website,
            ));

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
                companyRole: RoleEnum::ADMIN,
                emailVerifiedAt: now(),
            ));

            $this->tokenRepository->markUsed($token);

            UserRegistered::dispatch($user);

            return $user;
        }, attempts: 5);
    }
}
