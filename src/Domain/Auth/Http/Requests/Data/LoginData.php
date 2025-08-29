<?php

declare(strict_types=1);

namespace Domain\Auth\Http\Requests\Data;

readonly class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $rememberMe,
    ) {
    }
}
