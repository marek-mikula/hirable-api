<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;

class PositionBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', $id);
    }
}
