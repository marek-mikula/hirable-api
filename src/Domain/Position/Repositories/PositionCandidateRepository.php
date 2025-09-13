<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionCandidatePriorityEnum;
use Domain\Position\Models\Builders\PositionCandidateBuilder;
use Domain\Position\Models\Builders\PositionCandidateShareBuilder;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionProcessStep;
use Domain\Position\Repositories\Input\PositionCandidateStoreInput;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

final readonly class PositionCandidateRepository implements PositionCandidateRepositoryInterface
{
    public function index(User $user, Position $position, array $with = [], array $withCount = []): Collection
    {
        return PositionCandidate::query()
            ->with($with)
            ->withCount($withCount)
            ->wherePosition($position->id)
            // when user is HM, candidates need
            // to be shared with him
            ->when($user->company_role === RoleEnum::HIRING_MANAGER, function (PositionCandidateBuilder $query) use ($user): void {
                $query->whereHas('shares', function (PositionCandidateShareBuilder $query) use ($user): void {
                    $query->where('position_candidate_shares.user_id', $user->id);
                });
            })
            ->get();
    }

    public function store(PositionCandidateStoreInput $input): PositionCandidate
    {
        $positionCandidate = new PositionCandidate();

        $positionCandidate->position_id = $input->position->id;
        $positionCandidate->candidate_id = $input->candidate->id;
        $positionCandidate->application_id = $input->application->id;
        $positionCandidate->step_id = $input->step->id;
        $positionCandidate->priority = PositionCandidatePriorityEnum::NONE;

        throw_if(!$positionCandidate->save(), RepositoryException::stored(PositionCandidate::class));

        $positionCandidate->setRelation('position', $input->position);
        $positionCandidate->setRelation('candidate', $input->candidate);
        $positionCandidate->setRelation('application', $input->application);
        $positionCandidate->setRelation('step', $input->step);

        return $positionCandidate;
    }

    public function setScore(PositionCandidate $positionCandidate, array $score, int $totalScore): PositionCandidate
    {
        $positionCandidate->score = $score;
        $positionCandidate->total_score = $totalScore;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        return $positionCandidate;
    }

    public function setStep(
        PositionCandidate $positionCandidate,
        PositionProcessStep $positionProcessStep
    ): PositionCandidate {
        $positionCandidate->step_id = $positionProcessStep->id;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        $positionCandidate->setRelation('step', $positionProcessStep);

        return $positionCandidate;
    }

    public function setPriority(PositionCandidate $positionCandidate, PositionCandidatePriorityEnum $priority): PositionCandidate
    {
        $positionCandidate->priority = $priority;

        throw_if(!$positionCandidate->save(), RepositoryException::updated(PositionCandidate::class));

        return $positionCandidate;
    }
}
