<?php

namespace Support\Token\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;
use App\Repositories\Token\TokenRepositoryInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Services\TokenResolver;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    public const IDENTIFIER = 'token';

    private const PARAM_NAME = 'token';

    // checks the validity of a token passed
    // in query parameter that is matches the
    // given type and is a valid token for
    // specific route

    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly TokenResolver $tokenResolver,
    ) {
    }

    /**
     * @throws HttpException
     */
    public function handle(Request $request, \Closure $next, int ...$types): Response
    {
        $types = collectEnum($types, TokenTypeEnum::class)->all();

        // no token given
        if (empty($token = $request->get(self::PARAM_NAME))) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_MISSING);
        }

        try {
            $token = Crypt::decryptString($token); // try to decrypt the given token
        } catch (DecryptException) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_CORRUPTED);
        }

        $token = $this->tokenRepository->findByTokenAndType($token, ...$types);

        // check token validity
        if (! $token || $token->is_expired || $token->is_used) {
            throw new HttpException(responseCode: ResponseCodeEnum::TOKEN_INVALID);
        }

        // set token model to the resolver, so we don't have to
        // query it multiple times
        $this->tokenResolver->setToken($token);

        return $next($request);
    }

    /**
     * Returns string, which can be used in route definition
     */
    public static function apply(TokenTypeEnum ...$types): string
    {
        throw_if(empty($types), new \InvalidArgumentException('At least one token type needs to be specified.'));

        return vsprintf('%s:%s', [self::IDENTIFIER, collect($types)->pluck('value')->join(',')]);
    }
}
