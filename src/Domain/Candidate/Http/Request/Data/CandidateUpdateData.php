<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request\Data;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Candidate\Enums\GenderEnum;

readonly class CandidateUpdateData
{
    /**
     * @param string[] $keys
     * @param string[]|null $tags
     */
    public function __construct(
        public array $keys,
        public ?string $firstname,
        public ?string $lastname,
        public ?GenderEnum $gender,
        public ?LanguageEnum $language,
        public ?string $email,
        public ?string $phonePrefix,
        public ?string $phoneNumber,
        public ?string $linkedin,
        public ?string $instagram,
        public ?string $github,
        public ?string $portfolio,
        public ?Carbon $birthDate,
        public ?array $tags,
    ) {
    }

    public function hasKey(string $key): bool
    {
        return in_array($key, $this->keys);
    }
}
