<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Models\Builders;

use App\Models\Builders\Builder;

class ProcessStepBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', $id);
    }
}
