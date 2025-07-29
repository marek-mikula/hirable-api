<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\ProcessStep\Enums\StepEnum;

class PositionProcessStepBuilder extends Builder
{
    public function wherePosition(int $id): static
    {
        return $this->where('position_id', $id);
    }

    public function whereStep(StepEnum|string $step): static
    {
        return $this->where('step', $step);
    }
}
