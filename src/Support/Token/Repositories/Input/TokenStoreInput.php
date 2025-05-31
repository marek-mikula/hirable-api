<?php

declare(strict_types=1);

namespace Support\Token\Repositories\Input;

use Carbon\Carbon;
use Domain\User\Models\User;
use Support\Token\Enums\TokenTypeEnum;

readonly class TokenStoreInput
{
    public function __construct(
        public TokenTypeEnum $type,
        public array $data = [],
        public ?int $validMinutes = null,
        public ?Carbon $validUntil = null,
        public ?User $user = null,
    ) {
    }
}
