<?php

declare(strict_types=1);

namespace Domain\Company\Models\Builders;

use App\Models\Builders\Builder;

class CompanyBuilder extends Builder
{
    public function whereEmail(string $email): static
    {
        return $this->where('email', '=', $email);
    }
}
