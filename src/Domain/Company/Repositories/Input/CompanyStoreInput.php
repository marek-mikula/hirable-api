<?php

declare(strict_types=1);

namespace Domain\Company\Repositories\Input;

readonly class CompanyStoreInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $idNumber,
        public ?string $website,
    ) {
    }
}
