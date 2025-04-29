<?php

declare(strict_types=1);

namespace App\Repositories\Token;

use App\Exceptions\RepositoryException;
use App\Models\Token;
use App\Models\User;
use App\Repositories\Token\Input\StoreInput;
use Illuminate\Support\Str;
use Support\Token\Enums\TokenTypeEnum;

final class TokenRepository implements TokenRepositoryInterface
{
    public function store(StoreInput $input): Token
    {
        $token = new Token();

        // use either explicitly set valid minutes
        // or use the default value from config
        $validMinutes = $input->validMinutes ?: (int) config(sprintf('token.validity.%s', $input->type->value));

        throw_if($validMinutes <= 0, new \InvalidArgumentException('Validity time for token cannot be less than 0.'));

        $value = hash_hmac('sha256', Str::random(40), (string) config('app.key'));

        $token->user_id = $input->user?->id;
        $token->type = $input->type;
        $token->token = $value;
        $token->data = $input->data;
        $token->valid_until = now()->addMinutes($validMinutes);

        throw_if(!$token->save(), RepositoryException::stored(Token::class));

        $token->setRelation('user', $input->user);

        return $token;
    }

    public function findByTokenAndType(string $token, TokenTypeEnum ...$type): ?Token
    {
        /** @var Token|null $model */
        $model = Token::query()
            ->where('token', '=', $token)
            ->whereIn('type', collect($type)->pluck('value'))
            ->first();

        return $model;
    }

    public function findLatestByTypeAndEmail(TokenTypeEnum $type, string $email): ?Token
    {
        /** @var Token|null $model */
        $model = Token::query()
            ->where('type', '=', $type->value)
            ->where('data->email', '=', $email)
            ->latest('id')
            ->first();

        return $model;
    }

    public function findLatestByTypeAndUser(TokenTypeEnum $type, User $user): ?Token
    {
        /** @var Token|null $model */
        $model = Token::query()
            ->where('type', '=', $type->value)
            ->whereUser($user->id)
            ->latest('id')
            ->first();

        return $model;
    }

    public function delete(Token $token): void
    {
        throw_if(!$token->delete(), RepositoryException::deleted(Token::class));
    }

    public function markUsed(Token $token): Token
    {
        $token->used_at = now();

        throw_if(!$token->save(), RepositoryException::saved(Token::class));

        return $token;
    }
}
