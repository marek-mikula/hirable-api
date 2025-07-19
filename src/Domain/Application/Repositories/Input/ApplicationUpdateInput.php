<?php

declare(strict_types=1);

namespace Domain\Application\Repositories\Input;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Candidate\Models\Candidate;

readonly class ApplicationUpdateInput
{
    public function __construct(
        public ?Candidate $candidate,
        public LanguageEnum $language,
        public ?GenderEnum $gender,
        public SourceEnum $source,
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
    ) {
    }
}
