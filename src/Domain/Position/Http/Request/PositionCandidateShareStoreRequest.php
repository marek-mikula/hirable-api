<?php

declare(strict_types=1);

namespace Domain\Position\Http\Request;

use App\Http\Requests\AuthRequest;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\Position\Policies\PositionCandidateSharePolicy;
use Domain\Position\Validation\ValidateShare;

class PositionCandidateShareStoreRequest extends AuthRequest
{
    public function authorize(): bool
    {
        /** @see PositionCandidateSharePolicy::store() */
        return $this->user()->can('store', [PositionCandidateShare::class, $this->route('positionCandidate'), $this->route('position')]);
    }

    public function rules(): array
    {
        return [
            'hiringManagers' => [
                'required',
                'array',
            ],
            'hiringManagers.*' => [
                'required',
                'integer',
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

    public function getHiringManagers(): array
    {
        return $this->array('hiringManagers');
    }
}
