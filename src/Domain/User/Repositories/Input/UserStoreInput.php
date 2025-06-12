<?php

declare(strict_types=1);

namespace Domain\User\Repositories\Input;

use App\Enums\LanguageEnum;
use Carbon\Carbon;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;

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
        public bool $companyOwner,
        public ?string $phone = null,
        public ?string $prefix = null,
        public ?string $postfix = null,
        public ?Carbon $emailVerifiedAt = null,
    ) {
    }
}
