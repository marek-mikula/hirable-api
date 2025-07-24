<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\UseCases\UseCase;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\File\Actions\GetModelSubFoldersAction;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Services\FileMover;

class ProcessApplicationUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly FileMover $fileMover,
    ) {
    }

    public function handle(Application $application): Candidate
    {
        $application->loadMissing([
            'position',
            'position.company',
            'files',
        ]);

        // try to find duplicate candidate model
        // in company by email or phone number
        $existingCandidate = $this->candidateRepository->findDuplicateInCompany(
            company: $application->position->company,
            email: $application->email,
            phonePrefix: $application->phone_prefix,
            phoneNumber: $application->phone_number,
        );

        $input = new CandidateStoreInput(
            company: $application->position->company,
            language: $application->language,
            firstname: $application->firstname,
            lastname: $application->lastname,
            email: $application->email,
            phonePrefix: $application->phone_prefix,
            phoneNumber: $application->phone_number,
            linkedin: $application->linkedin,
        );

        $otherFiles = $application->files->filter(fn (File $file) => $file->type === FileTypeEnum::APPLICATION_OTHER);
        $cv = $application->files->first(fn (File $file) => $file->type === FileTypeEnum::APPLICATION_CV);

        return DB::transaction(function () use (
            $application,
            $existingCandidate,
            $input,
            $otherFiles,
            $cv,
        ): Candidate {
            // store candidate if no existing
            // candidate was found
            $candidate = $existingCandidate ?? $this->candidateRepository->store($input);

            // create connection between candidate
            // and position
            $this->positionCandidateRepository->store(
                $application->position,
                $candidate,
                $application,
            );

            // transfer other files from application to candidate
            if ($otherFiles->isNotEmpty()) {
                $this->fileMover->moveFiles(
                    fileable: $candidate,
                    type: FileTypeEnum::CANDIDATE_OTHER,
                    files: $otherFiles->all(),
                    folders: GetModelSubFoldersAction::make()->handle($candidate),
                );
            }

            // transfer CV from application to candidate
            if ($cv) {
                $this->fileMover->moveFiles(
                    fileable: $candidate,
                    type: FileTypeEnum::CANDIDATE_CV,
                    files: [$cv],
                    folders: GetModelSubFoldersAction::make()->handle($candidate),
                );
            }

            return $candidate;
        }, attempts: 5);
    }
}
