<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\Candidate\Http\Request\Data\CandidateStoreData;
use Domain\Candidate\Jobs\CreateCandidateFromCvJob;
use Domain\User\Models\User;
use Support\File\Enums\FileTypeEnum;
use Support\File\Services\FileSaver;

final class CandidateStoreUseCase extends UseCase
{
    public function __construct(
        private readonly FileSaver $fileSaver,
    ) {
    }

    public function handle(User $user, CandidateStoreData $data): void
    {
        foreach ($data->cvs as $cv) {
            $cv = $this->fileSaver->saveFile(
                file: $cv,
                path: 'candidates',
                type: FileTypeEnum::CANDIDATE_CV
            );

            CreateCandidateFromCvJob::dispatch(
                $user,
                $cv,
                null,
            );
        }
    }
}
