<?php

declare(strict_types=1);

namespace Domain\Application\Models\Builders;

use App\Models\Builders\Builder;

class ApplicationBuilder extends Builder
{
    public function wherePosition(int $id): static
    {
        return $this->where('position_id', $id);
    }
}
