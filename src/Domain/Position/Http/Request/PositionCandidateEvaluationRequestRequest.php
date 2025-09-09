<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Http\Request\Data\PositionCandidateEvaluationRequestData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Policies\PositionCandidateEvaluationPolicy;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Domain\User\Rules\UserOnPositionRule;

class PositionCandidateEvaluationRequestRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateEvaluationPolicy::request() */
        return $this->user()->can('request', [PositionCandidateEvaluation::class, $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        /** @var Position $position */
        $position = $this->route('position');

        return [
            'userId' => [
                'required',
                'integer',
                new UserOnPositionRule($position, [PositionRoleEnum::HIRING_MANAGER]),
            ],
            'fillUntil' => [
                'nullable',
                'date_format:Y-m-d',
            ]
        ];
    }

    public function toData(): PositionCandidateEvaluationRequestData
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = app(UserRepositoryInterface::class);

        /** @var User $user */
        $user = $userRepository->getByIdsAndCompany($this->user()->company, [(int) $this->input('userId')])->first();

        return new PositionCandidateEvaluationRequestData(
            user: $user,
            fillUntil: $this->date('fillUntil', 'Y-m-d'),
        );
    }
}
