<?php

declare(strict_types=1);

namespace App\Repositories\Token;

use App\Models\Token;
use App\Models\User;
use App\Repositories\Token\Input\StoreInput;
use Support\Token\Enums\TokenTypeEnum;

interface TokenRepositoryInterface
{
    public function store(StoreInput $input): Token;

    public function findByTokenAndType(string $token, TokenTypeEnum ...$type): ?Token;

    public function findLatestByTypeAndEmail(TokenTypeEnum $type, string $email): ?Token;

    public function findLatestByTypeAndUser(TokenTypeEnum $type, User $user): ?Token;

    public function delete(Token $token): void;

    public function markUsed(Token $token): Token;
}
