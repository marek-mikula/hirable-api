<?php

namespace App\Models\Builders;

class UserBuilder extends Builder
{
    public function whereEmail(string $email): static
    {
        return $this->where('email', '=', $email);
    }
}
