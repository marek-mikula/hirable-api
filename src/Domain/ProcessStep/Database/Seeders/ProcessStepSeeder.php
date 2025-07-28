<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Database\Seeders;

use Domain\ProcessStep\Enums\ProcessStepEnum;
use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProcessStepEnum::cases() as $step) {
            $processStep = new ProcessStep();
            $processStep->company_id = null;
            $processStep->step = $step;
            $processStep->is_fixed = $step->isFixed();
            $processStep->is_repeatable = $step->isRepeatable();
            $processStep->save();
        }
    }
}
