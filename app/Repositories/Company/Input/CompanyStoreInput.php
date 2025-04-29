<?php

declare(strict_types=1);

namespace App\Repositories\Company\Input;

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
