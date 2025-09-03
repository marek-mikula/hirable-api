<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Database\Seeders;

use Domain\Position\Enums\ActionTypeEnum;
use Domain\ProcessStep\Enums\StepEnum;
use Domain\ProcessStep\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            StepEnum::INTERVIEW->value => [
                'is_repeatable' => true,
                'triggers_action' => ActionTypeEnum::INTERVIEW,
            ],
            StepEnum::TASK->value => [
                'is_repeatable' => true,
                'triggers_action' => ActionTypeEnum::TASK,
            ],
            StepEnum::ASSESSMENT_CENTER->value => [
                'is_repeatable' => true,
                'triggers_action' => ActionTypeEnum::ASSESSMENT_CENTER,
            ],
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
