<?php

declare(strict_types=1);

namespace Domain\Company\Repositories\Input;

use App\Enums\LanguageEnum;

readonly class CompanyContactUpdateInput
{
    public function __construct(
        public LanguageEnum $language,
        public string $firstname,
        public string $lastname,
        public string $email,
        public ?string $note,
        public ?string $companyName,
    ) {
    }
}
