<?php

declare(strict_types=1);

namespace App\Repositories\Token\Input;

use App\Models\User;
use Support\Token\Enums\TokenTypeEnum;

readonly class TokenStoreInput
{
    public function __construct(
        public TokenTypeEnum $type,
        public array $data = [],
        public ?int $validMinutes = null,
        public ?User $user = null,
    ) {
    }
}
