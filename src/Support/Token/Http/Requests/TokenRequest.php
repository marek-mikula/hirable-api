<?php

declare(strict_types=1);

namespace Support\Token\Http\Requests;

use App\Http\Requests\Request;
use Support\Token\Models\Token;
use Tests\Process\TokenResolver;

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
