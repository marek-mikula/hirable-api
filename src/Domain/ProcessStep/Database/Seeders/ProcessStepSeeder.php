<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Database\Seeders;

use Domain\ProcessStep\Enums\StepEnum;
use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            StepEnum::INTERVIEW->value => ['is_repeatable' => true],
            StepEnum::TEST->value => ['is_repeatable' => true],
            StepEnum::TASK->value => ['is_repeatable' => true],
            StepEnum::ASSESSMENT_CENTER->value => ['is_repeatable' => true],
            StepEnum::BACKGROUND_CHECK->value => ['is_repeatable' => true],
            StepEnum::REFERENCE_CHECK->value => ['is_repeatable' => true],
        ];

        foreach ($steps as $step => $attributes) {
            $processStep = new ProcessStep();
            $processStep->company_id = null;
            $processStep->step = StepEnum::from($step);
            $processStep->fill($attributes);
            $processStep->save();
        }
    }
}
