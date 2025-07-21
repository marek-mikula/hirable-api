<?php

declare(strict_types=1);

namespace Domain\Company\Repositories\Input;

use App\Enums\LanguageEnum;

readonly class CompanyUpdateInput
{
    public function __construct(
        public LanguageEnum $language,
        public string $name,
        public string $email,
        public string $idNumber,
        public ?string $website,
    ) {
    }
}
