<?php

namespace Domain\Company\Http\Requests\Data;

use Domain\Company\Enums\RoleEnum;
use Spatie\LaravelData\Data;

class InvitationStoreData extends Data
{
    public RoleEnum $role;

    public string $email;
}
