<?php

declare(strict_types=1);

namespace App\Models\Builders;

class UserBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', '=', $id);
    }

    public function whereEmail(string $email): static
    {
        return $this->where('email', '=', $email);
    }
}
