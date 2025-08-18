<?php

declare(strict_types=1);

namespace Domain\Candidate\Repositories\Input;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Candidate\Enums\GenderEnum;

readonly class CandidateUpdateInput
{
    public function __construct(
        public LanguageEnum $language,
        public ?GenderEnum $gender,
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $phonePrefix,
        public string $phoneNumber,
        public ?string $linkedin,
        public ?string $instagram,
        public ?string $github,
        public ?string $portfolio,
        public ?Carbon $birthDate,
        public array $experience,
        public array $tags,
    ) {
    }
}
