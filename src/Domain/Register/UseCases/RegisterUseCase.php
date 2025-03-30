<?php

namespace Domain\Register\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Company\Input\StoreInput as CompanyStoreInput;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\Input\StoreInput as UserStoreInput;
use App\Repositories\User\UserRepositoryInterface;
use App\UseCases\UseCase;
use Domain\Company\Enums\RoleEnum;
use Domain\Register\Http\Requests\Data\RegisterData;
use Domain\Register\Notifications\RegisterRegisteredNotification;
use Illuminate\Support\Facades\DB;
use Support\Token\Enums\TokenTypeEnum;

class RegisterUseCase extends UseCase
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function handle(Token $token, RegisterData $data): User
    {
        throw_if(
            condition: ! $token->hasDataValue('email') || $token->type !== TokenTypeEnum::REGISTRATION,
            exception: new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID)
        );

        throw_if(
            condition: ! $data->company,
            exception: new \Exception('Basic registration needs company data.')
        );

        $email = (string) $token->getDataValue('email');

        return DB::transaction(function () use (
            $token,
            $data,
            $email,
        ): User {
            $company = $this->companyRepository->store(CompanyStoreInput::from([
                'name' => $data->company->name,
                'email' => $data->company->email,
                'idNumber' => $data->company->idNumber,
                'website' => $data->company->website,
            ]));

            $user = $this->userRepository->store(UserStoreInput::from([
                'language' => LanguageEnum::tryFrom(app()->getLocale()),
                'firstname' => $data->firstname,
                'lastname' => $data->lastname,
                'email' => $email,
                'password' => $data->password,
                'agreementIp' => $data->agreementIp,
                'agreementAcceptedAt' => $data->agreementAcceptedAt,
                // mark immediately email as verified, because
                // user has registered through a link, which he
                // received to his email => we can safely say
                // the email is verified
                'emailVerifiedAt' => now(),
                'company' => $company,
                'companyRole' => RoleEnum::ADMIN,
                'prefix' => null,
                'postfix' => null,
                'phone' => null,
            ]));

            $this->tokenRepository->delete($token);

            $user->notify(new RegisterRegisteredNotification());

            return $user;
        }, attempts: 5);
    }
}
