<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories\Input;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Candidate\Enums\GenderEnum;
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
        public ?GenderEnum $gender = null,
        public ?string $linkedin = null,
        public ?string $instagram = null,
        public ?string $github = null,
        public ?string $portfolio = null,
        public ?Carbon $birthDate = null,
        public array $experience = [],
        public array $tags = [],
    ) {
    }
}
