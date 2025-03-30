<?php

namespace Support\Token\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Token;
use Support\Token\Services\TokenResolver;

class TokenRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    /**
     * @throws \Exception
     */
    public function getToken(): Token
    {
        return once(function (): Token {
            /** @var TokenResolver $resolver */
            $resolver = app(TokenResolver::class);

            return $resolver->getToken();
        });
    }
}
