<?php

declare(strict_types=1);

namespace Domain\User\Repositories\Input;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;

readonly class UserUpdateInput
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public ?TimezoneEnum $timezone,
        public LanguageEnum $language,
        public ?string $prefix,
        public ?string $postfix,
        public ?string $phone,
    ) {
    }
}
