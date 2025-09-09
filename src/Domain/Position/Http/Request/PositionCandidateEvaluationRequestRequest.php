<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Http\Request\Data\PositionCandidateEvaluationRequestData;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Policies\PositionCandidateEvaluationPolicy;
use Domain\Position\Validation\ValidateEvaluationRequest;
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
            'hiringManagers' => [
                'required',
                'array',
            ],
            'hiringManagers.*' => [
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

    public function after(): array
    {
        /** @var PositionCandidate $positionCandidate */
        $positionCandidate = $this->route('positionCandidate');

        return [
            new ValidateEvaluationRequest($positionCandidate),
        ];
    }

    public function toData(): PositionCandidateEvaluationRequestData
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = app(UserRepositoryInterface::class);

        return new PositionCandidateEvaluationRequestData(
            hiringManagers: $userRepository->getByIdsAndCompany($this->user()->company, $this->array('hiringManagers')),
            fillUntil: $this->date('fillUntil', 'Y-m-d'),
        );
    }
}
