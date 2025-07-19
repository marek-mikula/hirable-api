<?php

declare(strict_types=1);

namespace Domain\AI\Data;

use Carbon\Carbon;
use Domain\Candidate\Enums\GenderEnum;
use Spatie\LaravelData\Data;

class CVData extends Data
{
    public ?GenderEnum $gender;

    public ?Carbon $birthDate;

    public ?string $instagram;

    public ?string $github;

    public ?string $portfolio;
}
