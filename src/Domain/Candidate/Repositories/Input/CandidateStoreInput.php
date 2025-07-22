<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories\Input;

use App\Enums\LanguageEnum;
use Domain\Company\Models\Company;

readonly class CandidateStoreInput
{
    public function __construct(
        public Company $company,
        public LanguageEnum $language,
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $phonePrefix,
        public string $phoneNumber,
        public ?string $linkedin,
    ) {
    }
}
