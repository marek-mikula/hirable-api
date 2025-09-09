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

        $users = Arr::get($data, 'users', []);

        $passes = $this->positionCandidate
            ->evaluations()
            ->where('state', EvaluationStateEnum::WAITING)
            ->pluck('user_id')
            ->every(fn (int $id) => !in_array($id, $users));

        if ($passes) {
            return;
        }

        $validator->errors()->add('users', __('validation.after_rules.position_candidate.evaluation_exists'));
    }
}
