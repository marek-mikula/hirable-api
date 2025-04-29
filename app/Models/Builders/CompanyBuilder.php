<?php

declare(strict_types=1);

namespace App\Models\Builders;

class CompanyBuilder extends Builder
{
    public function whereEmail(string $email): static
    {
        return $this->where('email', '=', $email);
    }
}
