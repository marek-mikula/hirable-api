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
        $steps = [
            ProcessStepEnum::INTERVIEW->value => ['is_repeatable' => true],
            ProcessStepEnum::TEST->value => ['is_repeatable' => true],
            ProcessStepEnum::TASK->value => ['is_repeatable' => true],
            ProcessStepEnum::ASSESSMENT_CENTER->value => ['is_repeatable' => true],
            ProcessStepEnum::BACKGROUND_CHECK->value => ['is_repeatable' => true],
            ProcessStepEnum::REFERENCE_CHECK->value => ['is_repeatable' => true],
        ];

        foreach ($steps as $step => $attributes) {
            $processStep = new ProcessStep();
            $processStep->company_id = null;
            $processStep->step = ProcessStepEnum::from($step);
            $processStep->fill($attributes);
            $processStep->save();
        }
    }
}
