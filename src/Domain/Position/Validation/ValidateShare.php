<?php

declare(strict_types=1);

namespace Domain\Position\Validation;

use Domain\Position\Models\PositionCandidate;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateShare
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
            ->shares()
            ->pluck('user_id')
            ->every(fn (int $id): bool => !in_array($id, $hiringManagers));

        if ($passes) {
            return;
        }

        $validator->errors()->add('hiringManagers', __('validation.after_rules.position_candidate.share_exists'));
    }
}
