<?php

declare(strict_types=1);

namespace Domain\Position\Database\Seeders;

use Domain\Position\Enums\PositionProcessStepEnum;
use Domain\Position\Models\PositionProcessStep;
use Illuminate\Database\Seeder;

class PositionProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PositionProcessStepEnum::cases() as $step) {
            $positionProcessStep = new PositionProcessStep();
            $positionProcessStep->step = $step;
            $positionProcessStep->company_id = null;
            $positionProcessStep->save();
        }
    }
}
