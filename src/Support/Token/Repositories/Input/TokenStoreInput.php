<?php

declare(strict_types=1);

namespace Support\Token\Repositories\Input;

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
