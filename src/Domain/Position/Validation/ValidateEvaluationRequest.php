<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Models\PositionCandidate;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateEvaluationRequest
{
    public function __construct(
        private readonly PositionCandidate $positionCandidate,
    ) {
    }

    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $hiringManagers = Arr::get($data, 'hiringManagers', []);

        $passes = $this->positionCandidate
            ->evaluations()
            ->where('state', EvaluationStateEnum::WAITING)
            ->pluck('user_id')
            ->every(fn (int $id) => !in_array($id, $hiringManagers));

        if ($passes) {
            return;
        }

        $validator->errors()->add('hiringManagers', __('validation.after_rules.position_candidate.evaluation_exists'));
    }
}
