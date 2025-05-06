<?php

declare(strict_types=1);

namespace Domain\Register\Http\Requests\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class RegisterData extends Data
{
    public string $firstname;

    public string $lastname;

    public string $password;

    public string $agreementIp;

    public Carbon $agreementAcceptedAt;

    public ?CompanyData $company = null;
}
