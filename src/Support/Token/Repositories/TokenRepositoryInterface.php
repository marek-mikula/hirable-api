<?php

declare(strict_types=1);

namespace Support\Token\Repositories;

use Domain\User\Models\User;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;
use Support\Token\Repositories\Input\TokenStoreInput;

interface TokenRepositoryInterface
{
    public function store(TokenStoreInput $input): Token;

    public function findByTokenAndType(string $token, TokenTypeEnum ...$types): ?Token;

    public function findLatestByTypeAndEmail(TokenTypeEnum $type, string $email): ?Token;

    public function findLatestByTypeAndUser(TokenTypeEnum $type, User $user): ?Token;

    public function delete(Token $token): void;

    public function markUsed(Token $token): Token;
}
