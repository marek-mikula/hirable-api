<?php

declare(strict_types=1);

namespace Support\Token\Actions;

use Lorisleiva\Actions\Action;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

class GetTokenLinkAction extends Action
{
    public function handle(Token $token): string
    {
        $uri = match ($token->type) {
            TokenTypeEnum::INVITATION => '/invitation?token={token}',
            TokenTypeEnum::REGISTRATION => '/register?token={token}',
            TokenTypeEnum::RESET_PASSWORD => '/password-reset?token={token}',
            TokenTypeEnum::EMAIL_VERIFICATION => '/verify-email?token={token}',
        };

        return frontendLink($uri, ['token' => $token->secret_token]);
    }
}
