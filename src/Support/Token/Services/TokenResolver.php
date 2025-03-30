<?php

namespace Support\Token\Services;

use App\Models\Token;
use Support\Token\Http\Middleware\TokenMiddleware;

class TokenResolver
{
    private ?Token $token = null;

    /**
     * @throws \Exception
     */
    public function getToken(): Token
    {
        if ($this->token === null) {
            throw new \Exception(
                message: vsprintf('No token found! Haven\'t you forgotten to add the %s to your route?', [TokenMiddleware::class])
            );
        }

        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }
}
