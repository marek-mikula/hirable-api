<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Token\Input\StoreInput;
use App\Repositories\Token\TokenRepositoryInterface;
use App\UseCases\UseCase;
use Domain\Company\Http\Requests\Data\InvitationStoreData;
use Domain\Company\Notifications\InvitationSentNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;

class StoreCompanyInvitationUseCase extends UseCase
{
    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
    ) {
    }

    public function handle(User $user, InvitationStoreData $data): Token
    {
        $invitationExists = Token::query()
            ->whereCompany($user->company_id)
            ->whereEmail($data->email)
            ->whereType(TokenTypeEnum::INVITATION)
            ->valid()
            ->exists();

        if ($invitationExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::INVITATION_EXISTS);
        }

        $userAlreadyExists = User::query()
            ->where('company_id', $user->company_id)
            ->where('email', $data->email)
            ->exists();

        if ($userAlreadyExists) {
            throw new HttpException(responseCode: ResponseCodeEnum::INVITATION_USER_EXISTS);
        }

        return DB::transaction(function () use (
            $user,
            $data,
        ): Token {
            $token = $this->tokenRepository->store(StoreInput::from([
                'type' => TokenTypeEnum::INVITATION,
                'user' => $user,
                'data' => [
                    'companyId' => $user->company_id,
                    'role' => $data->role->value,
                    'email' => $data->email,
                ],
            ]));

            Notification::route('mail', $data->email)->notify(new InvitationSentNotification(token: $token));

            return $token;
        }, attempts: 5);
    }
}
