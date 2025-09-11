<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Policies\PositionCandidateSharePolicy;
use Domain\Position\Validation\ValidateShare;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepositoryInterface;
use Domain\User\Rules\UserOnPositionRule;
use Illuminate\Database\Eloquent\Collection;

class PositionCandidateShareStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateSharePolicy::store() */
        return $this->user()->can('store', [PositionCandidateShare::class, $this->route('positionCandidate'), $this->route('position')]);
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
        ];
    }

    public function after(): array
    {
        /** @var PositionCandidate $positionCandidate */
        $positionCandidate = $this->route('positionCandidate');

        return [
            new ValidateShare($positionCandidate),
        ];
    }

    /**
     * @return Collection<User>
     */
    public function getHiringManagers(): Collection
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = app(UserRepositoryInterface::class);

        return $userRepository->getByIdsAndCompany($this->user()->company, $this->array('hiringManagers'));
    }
}
