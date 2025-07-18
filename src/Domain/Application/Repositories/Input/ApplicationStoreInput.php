<?php

declare(strict_types=1);

namespace Domain\Application\Repositories\Input;

use App\Enums\LanguageEnum;
use Domain\Candidate\Enums\SourceEnum;
use Domain\Position\Models\Position;

readonly class ApplicationStoreInput
{
    public function __construct(
        public Position $position,
        public LanguageEnum $language,
        public SourceEnum $source,
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $phonePrefix,
        public string $phoneNumber,
        public ?string $linkedin,
    ) {
    }
}
