<?php

declare(strict_types=1);

namespace Domain\Company\Models\Builders;

use App\Models\Builders\Builder;

class CompanyContactBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', $id);
    }
}
