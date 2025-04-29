<?php

declare(strict_types=1);

namespace App\Repositories\Company\Input;

use Spatie\LaravelData\Data;

class UpdateInput extends Data
{
    public string $name;

    public string $email;

    public string $idNumber;

    public ?string $website;
}
