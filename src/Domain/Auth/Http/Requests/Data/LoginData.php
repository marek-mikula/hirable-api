<?php

namespace Domain\Auth\Http\Requests\Data;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public string $email;

    public string $password;

    public bool $rememberMe;
}
