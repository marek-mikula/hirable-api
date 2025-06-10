<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\UseCases\UseCase;
use Domain\Company\Http\Requests\Data\InvitationStoreData;
use Domain\Company\Models\Company;
use Domain\Company\Notifications\InvitationSentNotification;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

class CompanyInvitationStoreUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(User $user, Company $company, InvitationStoreData $data): Token
    {
        $invitationExists = Token::query()
            ->whereCompany($company->id)
            ->whereEmail($data->email)
            ->whereType(TokenTypeEnum::INVITATION)
            ->valid()
            ->exists();

        if ($invitationExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::INVITATION_EXISTS);
        }

        $userAlreadyExists = User::query()
            ->whereCompany($company->id)
            ->where('email', $data->email)
            ->exists();

        if ($userAlreadyExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::INVITATION_USER_EXISTS);
        }

        return DB::transaction(function () use (
            $user,
            $company,
            $data,
        ): Token {
            $token = $this->tokenRepository->store(new TokenStoreInput(
                type: TokenTypeEnum::INVITATION,
                data: [
                    'companyId' => $company->id,
                    'role' => $data->role->value,
                    'email' => $data->email,
                ],
                user: $user,
            ));

            Notification::route('mail', $data->email)->notify(new InvitationSentNotification(token: $token));

            return $token;
        }, attempts: 5);
    }
}
