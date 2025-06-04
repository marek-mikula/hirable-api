<?php

declare(strict_types=1);

namespace Domain\Company\Http\Requests\Data;

use App\Enums\LanguageEnum;
use Spatie\LaravelData\Data;

class ContactStoreData extends Data
{
    public LanguageEnum $language;

    public string $firstname;

    public string $lastname;

    public string $email;

    public ?string $note;

    public ?string $companyName;
}
