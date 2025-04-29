<?php

declare(strict_types=1);

namespace App\Repositories\User\Input;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use Spatie\LaravelData\Data;

class UpdateInput extends Data
{
    public string $firstname;

    public string $lastname;

    public string $email;

    public ?TimezoneEnum $timezone;

    public bool $notificationTechnicalMail;

    public bool $notificationTechnicalApp;

    public bool $notificationMarketingMail;

    public bool $notificationMarketingApp;

    public bool $notificationApplicationMail;

    public bool $notificationApplicationApp;

    public LanguageEnum $language;

    public ?string $prefix;

    public ?string $postfix;

    public ?string $phone;
}
