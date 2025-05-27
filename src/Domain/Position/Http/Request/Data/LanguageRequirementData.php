<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request\Data;

use Spatie\LaravelData\Data;

class LanguageRequirementData extends Data
{
    public string $language;

    public string $level;
}
