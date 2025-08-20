<?php

declare(strict_types=1);

namespace Support\File\Policies;

use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Policies\CandidatePolicy;
use Domain\Position\Models\Position;
use Domain\Position\Policies\PositionPolicy;
use Domain\User\Models\User;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\ModelHasFileRepositoryInterface;

class FilePolicy
{
    public function __construct(
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
    ) {
    }

    public function download(User $user, File $file): bool
    {
        return $this->show($user, $file);
    }

    public function show(User $user, File $file): bool
    {
        return match ($file->type) {
            FileTypeEnum::CANDIDATE_CV,
            FileTypeEnum::CANDIDATE_OTHER => $this->showCandidateFile($user, $file),
            FileTypeEnum::POSITION_FILE => $this->showPositionFile($user, $file),
            default => false
        };
    }

    public function delete(User $user, File $file): bool
    {
        return match ($file->type) {
            FileTypeEnum::CANDIDATE_CV,
            FileTypeEnum::CANDIDATE_OTHER => $this->deleteCandidateFile($user, $file),
            FileTypeEnum::POSITION_FILE => $this->deletePositionFile($user, $file),
            default => false
        };
    }

    private function showPositionFile(User $user, File $file): bool
    {
        /** @var Position|null $position */
        $position = $this->modelHasFileRepository->getFileableOf($file, Position::class);

        if (empty($position)) {
            return false;
        }

        /** @see PositionPolicy::show() */
        return $user->can('show', $position);
    }

    private function deletePositionFile(User $user, File $file): bool
    {
        /** @var Position|null $position */
        $position = $this->modelHasFileRepository->getFileableOf($file, Position::class);

        if (empty($position)) {
            return false;
        }

        /** @see PositionPolicy::update() */
        return $user->can('update', $position);
    }

    private function showCandidateFile(User $user, File $file): bool
    {
        /** @var Candidate|null $candidate */
        $candidate = $this->modelHasFileRepository->getFileableOf($file, Candidate::class);

        if (empty($candidate)) {
            return false;
        }

        /** @see CandidatePolicy::show() */
        return $user->can('show', $candidate);
    }

    private function deleteCandidateFile(User $user, File $file): bool
    {
        /** @var Candidate|null $candidate */
        $candidate = $this->modelHasFileRepository->getFileableOf($file, Candidate::class);

        if (empty($candidate)) {
            return false;
        }

        /** @see CandidatePolicy::update() */
        return $user->can('update', $candidate);
    }
}
