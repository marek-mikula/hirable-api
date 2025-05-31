<?php

declare(strict_types=1);

namespace Domain\Company\Repositories\Input;

use App\Enums\LanguageEnum;
use Domain\Company\Models\Company;

readonly class CompanyContactStoreInput
{
    public function __construct(
        public Company $company,
        public LanguageEnum $language,
        public string $firstname,
        public string $lastname,
        public string $email,
        public ?string $note,
        public ?string $companyName,
    ) {
    }
}
