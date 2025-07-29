<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\UseCases\UseCase;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Domain\Candidate\Repositories\Input\CandidateStoreInput;
use Domain\Position\Repositories\Inputs\PositionCandidateStoreInput;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Domain\Position\Repositories\PositionProcessStepRepositoryInterface;
use Domain\ProcessStep\Enums\StepEnum;
use Illuminate\Support\Facades\DB;
use Support\File\Enums\FileTypeEnum;
use Support\File\Models\File;
use Support\File\Repositories\ModelHasFileRepositoryInterface;
use Support\File\Services\FileMover;
use Support\File\Services\FilePathService;

class ProcessApplicationUseCase extends UseCase
{
    public function __construct(
        private readonly PositionProcessStepRepositoryInterface $positionProcessStepRepository,
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
        private readonly ModelHasFileRepositoryInterface $modelHasFileRepository,
        private readonly CandidateRepositoryInterface $candidateRepository,
        private readonly FilePathService $filePathService,
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

        $positionProcessStep = $this->positionProcessStepRepository->findByPosition(
            $application->position,
            StepEnum::NEW,
        );

        throw_if(empty($positionProcessStep), new \Exception('Missing default position process step.'));

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

        $otherFiles = $application->files->filter(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_OTHER);
        $cv = $application->files->first(fn (File $file) => $file->type === FileTypeEnum::CANDIDATE_CV);

        return DB::transaction(function () use (
            $application,
            $positionProcessStep,
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
                new PositionCandidateStoreInput(
                    position: $application->position,
                    candidate: $candidate,
                    application: $application,
                    step: $positionProcessStep,
                )
            );

            // transfer CV from application to candidate
            if ($cv) {
                $cv = $this->fileMover->moveFile(
                    file: $cv,
                    path: $this->filePathService->getPathForModel($candidate),
                );

                $this->modelHasFileRepository->store($candidate, $cv);
            }

            // transfer other files from application to candidate
            if ($otherFiles->isNotEmpty()) {
                foreach ($otherFiles as $otherFile) {
                    $otherFile = $this->fileMover->moveFile(
                        file: $otherFile,
                        path: $this->filePathService->getPathForModel($candidate),
                    );

                    $this->modelHasFileRepository->store($candidate, $otherFile);
                }
            }

            return $candidate;
        }, attempts: 5);
    }
}
