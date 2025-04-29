<?php

declare(strict_types=1);

namespace Domain\Register\Http\Requests\Data;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{
    public string $name;

    public string $email;

    public string $idNumber;

    public ?string $website;
}
