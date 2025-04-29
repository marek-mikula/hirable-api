<?php

declare(strict_types=1);

namespace App\Repositories\User\Input;

use App\Enums\LanguageEnum;
use App\Models\Company;
use Carbon\Carbon;
use Domain\Company\Enums\RoleEnum;

readonly class UserStoreInput
{
    public function __construct(
        public ?LanguageEnum $language,
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $password,
        public string $agreementIp,
        public Carbon $agreementAcceptedAt,
        public Company $company,
        public RoleEnum $companyRole,
        public ?string $phone = null,
        public ?string $prefix = null,
        public ?string $postfix = null,
        public ?Carbon $emailVerifiedAt = null,
    ) {
    }
}
