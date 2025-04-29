<?php

declare(strict_types=1);

namespace App\Repositories\User\Input;

use App\Enums\LanguageEnum;
use App\Models\Company;
use Carbon\Carbon;
use Domain\Company\Enums\RoleEnum;
use Spatie\LaravelData\Data;

class StoreInput extends Data
{
    public ?LanguageEnum $language;

    public string $firstname;

    public string $lastname;

    public string $email;

    public string $password;

    public string $agreementIp;

    public Carbon $agreementAcceptedAt;

    public ?Carbon $emailVerifiedAt = null;

    public Company $company;

    public RoleEnum $companyRole;

    public ?string $prefix;

    public ?string $postfix;

    public ?string $phone;
}
